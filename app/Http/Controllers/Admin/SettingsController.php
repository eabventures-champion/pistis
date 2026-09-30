<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $logoPath = Setting::get('store_logo_path');
        if ($logoPath) {
            $fullPath = storage_path('app/public/' . $logoPath);
            $v = file_exists($fullPath) ? filemtime($fullPath) : time();
            $logoUrl = asset('storage/' . $logoPath) . '?v=' . $v;
        } else {
            $logoUrl = Setting::get('store_logo_url');
        }

        $settings = [
            'store_name' => Setting::get('store_name', 'Pistis'),
            'store_email' => Setting::get('store_email', ''),
            'store_phone' => Setting::get('store_phone', ''),
            'store_address' => Setting::get('store_address', ''),
            'active_payment_gateway' => Setting::get('active_payment_gateway', config('services.active_payment_gateway', 'paystack')),
            'shopify_store_url' => config('services.shopify.store_url'),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            'currency_code' => Setting::get('currency_code', 'USD'),
            'store_logo_url' => $logoUrl,
            'store_logo_height' => (int) Setting::get('store_logo_height', 32),
            'store_hide_brand_text' => (bool) Setting::get('store_hide_brand_text', false),
            'homepage_show_categories' => (bool) Setting::get('homepage_show_categories', '1'),
            'homepage_show_editorial_categories' => (bool) Setting::get('homepage_show_editorial_categories', '1'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'nullable|string|max:255',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|string|max:50',
            'store_address' => 'nullable|string|max:500',
            'active_payment_gateway' => 'nullable|string|max:50',
            'currency_symbol' => 'nullable|string|max:10',
            'currency_code' => 'nullable|string|max:10',
            'store_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp,gif|max:5120',
            'store_logo_url_input' => 'nullable|url|max:1000',
            'store_logo_height' => 'nullable|integer|min:16|max:120',
            'store_hide_brand_text' => 'nullable|boolean',
            'homepage_show_categories' => 'nullable|boolean',
            'homepage_show_editorial_categories' => 'nullable|boolean',
        ]);

        $fields = [
            'store_name', 'store_email', 'store_phone', 'store_address',
            'active_payment_gateway', 'currency_symbol', 'currency_code',
            'store_logo_height',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, (string) $request->input($field));
            }
        }

        Setting::set('store_hide_brand_text', $request->boolean('store_hide_brand_text') ? '1' : '0');
        Setting::set('homepage_show_categories', $request->boolean('homepage_show_categories') ? '1' : '0');
        Setting::set('homepage_show_editorial_categories', $request->boolean('homepage_show_editorial_categories') ? '1' : '0');

        // Handle Logo file upload
        if ($request->hasFile('store_logo')) {
            $oldPath = Setting::get('store_logo_path');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $newPath = $request->file('store_logo')->store('branding', 'public');
            
            // Auto-trim transparent margins so logo renders prominently
            $fullPath = storage_path('app/public/' . $newPath);
            $this->trimImageBorders($fullPath);

            Setting::set('store_logo_path', $newPath);
            Setting::set('store_logo_url', '');
        } elseif ($request->filled('store_logo_url_input')) {
            Setting::set('store_logo_url', $request->input('store_logo_url_input'));
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings and brand logo updated successfully!');
    }

    public function removeLogo()
    {
        $oldPath = Setting::get('store_logo_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        Setting::set('store_logo_path', '');
        Setting::set('store_logo_url', '');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Brand logo removed successfully.');
    }

    /**
     * Automatically trim transparent or excess margins from uploaded logo images.
     */
    private function trimImageBorders(string $fullPath): void
    {
        if (!file_exists($fullPath)) return;

        $info = @getimagesize($fullPath);
        if (!$info) return;

        $mime = $info['mime'] ?? '';
        $im = null;
        if ($mime === 'image/png') {
            $im = @imagecreatefrompng($fullPath);
        } elseif ($mime === 'image/jpeg') {
            $im = @imagecreatefromjpeg($fullPath);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $im = @imagecreatefromwebp($fullPath);
        }

        if (!$im) return;

        $w = imagesx($im);
        $h = imagesy($im);
        $minX = $w; $maxX = 0; $minY = $h; $maxY = 0;

        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $rgba = imagecolorat($im, $x, $y);
                $alpha = ($rgba & 0x7F000000) >> 24;
                $r = ($rgba >> 16) & 0xFF;
                $g = ($rgba >> 8) & 0xFF;
                $b = $rgba & 0xFF;
                if ($alpha < 120 && ($r < 245 || $g < 245 || $b < 245)) {
                    if ($x < $minX) $minX = $x;
                    if ($x > $maxX) $maxX = $x;
                    if ($y < $minY) $minY = $y;
                    if ($y > $maxY) $maxY = $y;
                }
            }
        }

        if ($minX <= $maxX && $minY <= $maxY) {
            $pad = 6;
            $minX = max(0, $minX - $pad);
            $minY = max(0, $minY - $pad);
            $maxX = min($w - 1, $maxX + $pad);
            $maxY = min($h - 1, $maxY + $pad);
            $cw = $maxX - $minX + 1;
            $ch = $maxY - $minY + 1;

            if ($cw < $w || $ch < $h) {
                $trimmed = imagecreatetruecolor($cw, $ch);
                imagealphablending($trimmed, false);
                imagesavealpha($trimmed, true);
                $transparent = imagecolorallocatealpha($trimmed, 255, 255, 255, 127);
                imagefilledrectangle($trimmed, 0, 0, $cw, $ch, $transparent);
                imagecopy($trimmed, $im, 0, 0, $minX, $minY, $cw, $ch);

                if ($mime === 'image/png') {
                    imagepng($trimmed, $fullPath, 9);
                } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
                    imagewebp($trimmed, $fullPath, 95);
                } elseif ($mime === 'image/jpeg') {
                    imagejpeg($trimmed, $fullPath, 95);
                }

                imagedestroy($trimmed);
            }
        }
        imagedestroy($im);
    }
}
