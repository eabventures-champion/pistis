<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::active()
            ->ordered()
            ->get();

        // Editorial Selection: Load featured & in-stock products with full category hierarchy
        $featuredProducts = Product::active()
            ->featured()
            ->inStock()
            ->with(['category.parent.parent.parent'])
            ->latest()
            ->take(32)
            ->get();

        // Ensure rich selection across all categories
        if ($featuredProducts->count() < 8) {
            $existingIds = $featuredProducts->pluck('id');
            $additional = Product::active()
                ->inStock()
                ->whereNotIn('id', $existingIds)
                ->with(['category.parent.parent.parent'])
                ->latest()
                ->take(16)
                ->get();
            $featuredProducts = $featuredProducts->concat($additional);
        }

        // Active parent categories for badges filter next to Editorial Selection
        $parentCategories = Category::active()
            ->topLevel()
            ->orderBy('sort_order')
            ->get();

        $categories = Category::active()
            ->topLevel()
            ->with(['children.children', 'products'])
            ->get();

        $newArrivals = Product::active()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('heroSlides', 'featuredProducts', 'categories', 'parentCategories', 'newArrivals'));
    }

    /**
     * Dynamic Event & Social Media Connect Hub
     * Mobile-first landing page reached when visitors scan the Pistis QR code at events.
     * Automatically reflects any handles updated or added in the admin dashboard.
     */
    public function socialConnect()
    {
        $primaryHandle = Setting::get('social_primary_handle', '@wearpistis');
        $tagline = Setting::get('social_floating_tagline', 'Official Atelier & Runway Archive');
        $storeName = Setting::get('store_name', 'Pistis');

        $logoPath = Setting::get('store_logo_path');
        if ($logoPath) {
            $fullPath = storage_path('app/public/' . $logoPath);
            $v = file_exists($fullPath) ? filemtime($fullPath) : time();
            $logoUrl = asset('storage/' . $logoPath) . '?v=' . $v;
        } else {
            $logoUrl = Setting::get('store_logo_url');
        }

        $formatUrl = function($val, $prefix) {
            if (empty($val)) return null;
            $val = trim($val);
            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                return $val;
            }
            return $prefix . ltrim($val, '@');
        };

        $channels = [];

        // 1. Instagram
        $instagram = Setting::get('social_instagram');
        if (!empty($instagram)) {
            $channels[] = [
                'key' => 'instagram',
                'name' => 'Instagram',
                'handle' => $instagram,
                'url' => $formatUrl($instagram, 'https://instagram.com/'),
                'subtitle' => 'Runway Premiere & Capsule Drops',
                'badge' => 'Exclusive Drops',
                'color' => '#E1306C',
                'gradient' => 'linear-gradient(135deg, #833ab4 0%, #fd1d1d 50%, #fcb045 100%)',
                'icon' => 'instagram',
            ];
        }

        // 2. TikTok
        $tiktok = Setting::get('social_tiktok');
        if (!empty($tiktok)) {
            $channels[] = [
                'key' => 'tiktok',
                'name' => 'TikTok',
                'handle' => $tiktok,
                'url' => $formatUrl($tiktok, 'https://tiktok.com/@'),
                'subtitle' => 'Behind The Scenes & Motion Archive',
                'badge' => 'Video Archive',
                'color' => '#00f2fe',
                'gradient' => 'linear-gradient(135deg, #00f2fe 0%, #fe0979 100%)',
                'icon' => 'tiktok',
            ];
        }

        // 3. WhatsApp Concierge
        $whatsapp = Setting::get('social_whatsapp');
        if (!empty($whatsapp)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $whatsapp);
            $channels[] = [
                'key' => 'whatsapp',
                'name' => 'WhatsApp VIP Concierge',
                'handle' => $whatsapp,
                'url' => str_starts_with($whatsapp, 'http') ? $whatsapp : 'https://wa.me/' . $cleanPhone,
                'subtitle' => 'Private Client Liaison & Bespoke Inquiries',
                'badge' => 'Direct Concierge',
                'color' => '#25D366',
                'gradient' => 'linear-gradient(135deg, #128c7e 0%, #25d366 100%)',
                'icon' => 'whatsapp',
            ];
        }

        // 4. X (Twitter)
        $twitter = Setting::get('social_twitter');
        if (!empty($twitter)) {
            $channels[] = [
                'key' => 'twitter',
                'name' => 'X (formerly Twitter)',
                'handle' => $twitter,
                'url' => $formatUrl($twitter, 'https://x.com/'),
                'subtitle' => 'Atelier Dispatch & Official Press',
                'badge' => 'Official Press',
                'color' => '#ffffff',
                'gradient' => 'linear-gradient(135deg, #18181b 0%, #27272a 100%)',
                'icon' => 'twitter',
            ];
        }

        // 5. YouTube
        $youtube = Setting::get('social_youtube');
        if (!empty($youtube)) {
            $channels[] = [
                'key' => 'youtube',
                'name' => 'YouTube',
                'handle' => $youtube,
                'url' => $formatUrl($youtube, 'https://youtube.com/@'),
                'subtitle' => 'Seasonal Runway Films & Documentaries',
                'badge' => 'Runway Films',
                'color' => '#FF0000',
                'gradient' => 'linear-gradient(135deg, #b91c1c 0%, #ef4444 100%)',
                'icon' => 'youtube',
            ];
        }

        // 6. Pinterest
        $pinterest = Setting::get('social_pinterest');
        if (!empty($pinterest)) {
            $channels[] = [
                'key' => 'pinterest',
                'name' => 'Pinterest',
                'handle' => $pinterest,
                'url' => $formatUrl($pinterest, 'https://pinterest.com/'),
                'subtitle' => 'Moodboards, Silhouettes & Swatches',
                'badge' => 'Editorial Moods',
                'color' => '#E60023',
                'gradient' => 'linear-gradient(135deg, #991b1b 0%, #dc2626 100%)',
                'icon' => 'pinterest',
            ];
        }

        // 7. Facebook
        $facebook = Setting::get('social_facebook');
        if (!empty($facebook)) {
            $channels[] = [
                'key' => 'facebook',
                'name' => 'Facebook',
                'handle' => $facebook,
                'url' => $formatUrl($facebook, 'https://facebook.com/'),
                'subtitle' => 'Community & Runway Announcements',
                'badge' => 'Community',
                'color' => '#1877F2',
                'gradient' => 'linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%)',
                'icon' => 'facebook',
            ];
        }

        return view('social-connect', compact(
            'channels',
            'primaryHandle',
            'tagline',
            'storeName',
            'logoUrl'
        ));
    }
}
