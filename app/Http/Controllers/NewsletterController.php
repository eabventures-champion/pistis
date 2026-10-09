<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NewsletterController extends Controller
{
    /**
     * Handle public Inner Circle newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower(trim($validated['email']));

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->status === 'active') {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'already_subscribed' => true,
                        'message' => 'You are already a member of the Pistis Inner Circle.',
                    ]);
                }
                return back()->with('newsletter_info', 'You are already a member of the Pistis Inner Circle.');
            }

            // Reactivate subscriber if previously unsubscribed
            $subscriber->update([
                'status' => 'active',
                'unsubscribed_at' => null,
                'subscribed_at' => Carbon::now(),
                'ip_address' => $request->ip(),
            ]);

            $message = 'Welcome back! Your subscription to the Pistis Inner Circle has been restored.';
        } else {
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'status' => 'active',
                'source' => 'footer_inner_circle',
                'ip_address' => $request->ip(),
                'subscribed_at' => Carbon::now(),
            ]);

            $message = 'Welcome to the Pistis Inner Circle. Private previews and lookbooks will be delivered to your inbox.';
        }

        // Send automated editorial welcome lookbook email
        try {
            \Illuminate\Support\Facades\Mail::to($subscriber->email)
                ->send(new \App\Mail\InnerCircleWelcomeMail($subscriber));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Could not dispatch welcome email to {$subscriber->email}: " . $e->getMessage());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('newsletter_success', $message);
    }
}
