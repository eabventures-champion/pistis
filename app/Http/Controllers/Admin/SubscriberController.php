<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->input('view', 'all');
        $query = NewsletterSubscriber::query();

        if ($view === 'active') {
            $query->where('status', 'active');
        } elseif ($view === 'unsubscribed') {
            $query->where('status', 'unsubscribed');
        }

        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%{$search}%");
        }

        $counts = [
            'all' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('status', 'active')->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
            'this_month' => NewsletterSubscriber::where('status', 'active')
                ->where('subscribed_at', '>=', Carbon::now()->startOfMonth())
                ->count(),
        ];

        $subscribers = $query->latest('subscribed_at')->paginate(25)->withQueryString();

        return view('admin.subscribers.index', compact('subscribers', 'counts', 'view'));
    }

    /**
     * Export subscribers as CSV file for Mailchimp, Klaviyo, Brevo or spreadsheet use.
     */
    public function export(Request $request)
    {
        $status = $request->input('status', 'active');
        $query = NewsletterSubscriber::query();

        if ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'unsubscribed') {
            $query->where('status', 'unsubscribed');
        }

        $filename = 'pistis_inner_circle_' . ($status ?: 'all') . '_' . date('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, ['Email', 'Status', 'Subscribed At', 'Unsubscribed At', 'Source', 'IP Address']);

            $query->chunk(200, function ($subscribers) use ($handle) {
                foreach ($subscribers as $sub) {
                    fputcsv($handle, [
                        $sub->email,
                        ucfirst($sub->status),
                        $sub->subscribed_at ? $sub->subscribed_at->format('Y-m-d H:i:s') : '',
                        $sub->unsubscribed_at ? $sub->unsubscribed_at->format('Y-m-d H:i:s') : '',
                        $sub->source ?: 'footer',
                        $sub->ip_address ?: '',
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Toggle subscriber status between active and unsubscribed.
     */
    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        if ($subscriber->status === 'active') {
            $subscriber->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => Carbon::now(),
            ]);
            $msg = "Subscription for {$subscriber->email} has been disabled.";
        } else {
            $subscriber->update([
                'status' => 'active',
                'unsubscribed_at' => null,
            ]);
            $msg = "Subscription for {$subscriber->email} is now active.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete a subscriber record.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $email = $subscriber->email;
        $subscriber->delete();

        return back()->with('success', "Subscriber {$email} has been removed.");
    }

    /**
     * Show the lookbook / editorial campaign composer.
     */
    public function campaign()
    {
        $activeCount = NewsletterSubscriber::where('status', 'active')->count();
        $adminEmail = auth()->user()->email ?? config('mail.from.address', 'admin@pistis.com.au');

        return view('admin.subscribers.campaign', compact('activeCount', 'adminEmail'));
    }

    /**
     * Dispatch the editorial lookbook campaign to active subscribers or as a test email.
     */
    public function sendCampaign(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'headline' => 'required|string|max:255',
            'content' => 'required|string',
            'preheader' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'banner_url' => 'nullable|string|max:500',
            'mode' => 'required|in:test,broadcast',
            'test_email' => 'required_if:mode,test|nullable|email',
        ]);

        if ($validated['mode'] === 'test') {
            try {
                \Illuminate\Support\Facades\Mail::to($validated['test_email'])
                    ->send(new \App\Mail\EditorialCampaignMail(
                        emailSubject: '[TEST] ' . $validated['subject'],
                        headline: $validated['headline'],
                        messageContent: $validated['content'],
                        preheader: $validated['preheader'],
                        ctaText: $validated['cta_text'],
                        ctaUrl: $validated['cta_url'],
                        bannerUrl: $validated['banner_url'],
                        recipientEmail: $validated['test_email']
                    ));

                return back()->with('success', "Test campaign preview dispatched successfully to {$validated['test_email']}. Check your inbox!");
            } catch (\Throwable $e) {
                return back()->withErrors(['test_email' => 'Failed to send test email: ' . $e->getMessage()])->withInput();
            }
        }

        // Live broadcast to all active subscribers
        $subscribers = NewsletterSubscriber::where('status', 'active')->get();

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['error' => 'No active subscribers found to receive this campaign.'])->withInput();
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                \Illuminate\Support\Facades\Mail::to($subscriber->email)
                    ->send(new \App\Mail\EditorialCampaignMail(
                        emailSubject: $validated['subject'],
                        headline: $validated['headline'],
                        messageContent: $validated['content'],
                        preheader: $validated['preheader'],
                        ctaText: $validated['cta_text'],
                        ctaUrl: $validated['cta_url'],
                        bannerUrl: $validated['banner_url'],
                        recipientEmail: $subscriber->email
                    ));
                $sentCount++;
            } catch (\Throwable $e) {
                $failedCount++;
                \Illuminate\Support\Facades\Log::warning("Failed to send campaign to {$subscriber->email}: " . $e->getMessage());
            }
        }

        $msg = "Lookbook campaign successfully dispatched to {$sentCount} active subscribers.";
        if ($failedCount > 0) {
            $msg .= " ({$failedCount} failed to deliver).";
        }

        return redirect()->route('admin.subscribers.index')->with('success', $msg);
    }
}
