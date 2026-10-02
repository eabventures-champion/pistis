<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignVideoController extends Controller
{
    /**
     * Show the campaign / intro video settings and preview.
     */
    public function index()
    {
        $settings = [
            'enabled' => (bool) Setting::get('campaign_video_enabled', false),
            'video_path' => Setting::get('campaign_video_path', null),
            'video_url' => Setting::get('campaign_video_url', null),
            'delay' => (int) Setting::get('campaign_video_delay', 5),
            'badge' => Setting::get('campaign_video_badge', 'PISTIS EDITORIAL · RUNWAY PREMIERE'),
            'title' => Setting::get('campaign_video_title', 'THE NEW LOOKBOOK IN MOTION'),
            'description' => Setting::get('campaign_video_description', 'Discover the drape, texture, and refined silhouettes of our latest luxury clothing collection.'),
            'cta_text' => Setting::get('campaign_video_cta_text', 'SHOP THE COLLECTION'),
            'cta_url' => Setting::get('campaign_video_cta_url', '/shop'),
            'target_page' => Setting::get('campaign_video_target_page', 'homepage'),
            'frequency' => Setting::get('campaign_video_frequency', 'once_per_session'),
        ];

        // Resolved video source URL for display
        $activeVideoUrl = null;
        if (!empty($settings['video_path'])) {
            $activeVideoUrl = asset('storage/' . $settings['video_path']);
        } elseif (!empty($settings['video_url'])) {
            $activeVideoUrl = $settings['video_url'];
        }

        return view('admin.campaign-video.index', compact('settings', 'activeVideoUrl'));
    }

    /**
     * Update campaign / intro video settings and handle video upload.
     */
    public function update(Request $request)
    {
        $request->validate([
            'campaign_video_enabled' => 'nullable|boolean',
            'campaign_video_delay' => 'required|integer|min:1|max:120',
            'campaign_video_file' => 'nullable|file|mimes:mp4,webm,mov,ogg,mkv|max:102400',
            'campaign_video_url' => 'nullable|url|max:1000',
            'campaign_video_badge' => 'nullable|string|max:100',
            'campaign_video_title' => 'required|string|max:255',
            'campaign_video_description' => 'nullable|string|max:1000',
            'campaign_video_cta_text' => 'nullable|string|max:100',
            'campaign_video_cta_url' => 'nullable|string|max:500',
            'campaign_video_target_page' => 'required|in:homepage,all',
            'campaign_video_frequency' => 'required|in:once_on_site,once_per_session,always,once_per_day',
        ]);

        $enabled = $request->boolean('campaign_video_enabled') ? '1' : '0';
        Setting::set('campaign_video_enabled', $enabled);
        Setting::set('campaign_video_delay', (string) $request->input('campaign_video_delay', 5));
        Setting::set('campaign_video_badge', (string) $request->input('campaign_video_badge', ''));
        Setting::set('campaign_video_title', (string) $request->input('campaign_video_title', ''));
        Setting::set('campaign_video_description', (string) $request->input('campaign_video_description', ''));
        Setting::set('campaign_video_cta_text', (string) $request->input('campaign_video_cta_text', 'SHOP THE COLLECTION'));
        Setting::set('campaign_video_cta_url', (string) $request->input('campaign_video_cta_url', '/shop'));
        Setting::set('campaign_video_target_page', (string) $request->input('campaign_video_target_page', 'homepage'));
        Setting::set('campaign_video_frequency', (string) $request->input('campaign_video_frequency', 'once_per_session'));

        // Handle direct video file upload
        if ($request->hasFile('campaign_video_file')) {
            $oldPath = Setting::get('campaign_video_path');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $newPath = $request->file('campaign_video_file')->store('videos', 'public');
            Setting::set('campaign_video_path', $newPath);
            Setting::set('campaign_video_url', '');
        } elseif ($request->filled('campaign_video_url')) {
            Setting::set('campaign_video_url', $request->input('campaign_video_url'));
        }

        return redirect()->route('admin.campaign-video.index')
            ->with('success', 'Campaign video settings updated successfully!');
    }

    /**
     * Remove the current uploaded video.
     */
    public function removeVideo()
    {
        $oldPath = Setting::get('campaign_video_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        Setting::set('campaign_video_path', '');
        Setting::set('campaign_video_url', '');

        return redirect()->route('admin.campaign-video.index')
            ->with('success', 'Campaign video removed successfully.');
    }
}
