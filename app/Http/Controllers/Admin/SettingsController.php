<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
            'default_details_and_fit' => Setting::get('default_details_and_fit', "Heavyweight 390 GSM premium cotton fleece fabrication\nVintage pigment dye treatment for deep, washed texture\nRelaxed 90s fit with dropped shoulders and structured drape\nRib-knit collar, cuffs, and hem with reinforced needle stitching\nSignature archival branding and functional kangaroo pocket"),
            'default_shipping_and_returns' => Setting::get('default_shipping_and_returns', "All orders are dispatched from our atelier within 24–48 hours with full tracking details sent via email.\n\nComplimentary exchanges and returns are accepted within 14 days of delivery. Items must be in original unworn condition with tags attached."),
            'default_garment_care' => Setting::get('default_garment_care', "Machine wash cold inside-out on gentle cycle with like colors.\n\nDo not bleach. Lay flat to dry or tumble dry on lowest temperature.\n\nCool iron on reverse if necessary; do not iron directly on graphic accents."),
            'social_floating_enabled' => (bool) Setting::get('social_floating_enabled', true),
            'social_floating_position' => Setting::get('social_floating_position', 'bottom-left'),
            'social_primary_handle' => Setting::get('social_primary_handle', '@pistisofficial'),
            'social_floating_tagline' => Setting::get('social_floating_tagline', 'Official Atelier & Runway Archive'),
            'social_instagram' => Setting::get('social_instagram', 'https://instagram.com/pistisofficial'),
            'social_tiktok' => Setting::get('social_tiktok', 'https://tiktok.com/@pistisofficial'),
            'social_twitter' => Setting::get('social_twitter', 'https://x.com/pistisofficial'),
            'social_youtube' => Setting::get('social_youtube', ''),
            'social_pinterest' => Setting::get('social_pinterest', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_whatsapp' => Setting::get('social_whatsapp', ''),
            'social_show_in_footer' => (bool) Setting::get('social_show_in_footer', true),
            'auspost_api_key' => Setting::get('auspost_api_key', config('services.auspost.api_key', '')),
            'auspost_origin_postcode' => Setting::get('auspost_origin_postcode', config('services.auspost.origin_postcode', '2000')),
            'auspost_default_shipping_cost' => Setting::get('auspost_default_shipping_cost', config('services.auspost.default_shipping_cost', '12.00')),
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
            'default_details_and_fit' => 'nullable|string',
            'default_shipping_and_returns' => 'nullable|string',
            'default_garment_care' => 'nullable|string',
            'social_floating_enabled' => 'nullable|boolean',
            'social_floating_position' => 'nullable|string|in:bottom-left,bottom-right,left-center,right-center',
            'social_primary_handle' => 'nullable|string|max:100',
            'social_floating_tagline' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_tiktok' => 'nullable|string|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'social_pinterest' => 'nullable|string|max:255',
            'social_facebook' => 'nullable|string|max:255',
            'social_whatsapp' => 'nullable|string|max:255',
            'social_show_in_footer' => 'nullable|boolean',
            'auspost_api_key' => 'nullable|string|max:255',
            'auspost_origin_postcode' => 'nullable|string|max:20',
            'auspost_default_shipping_cost' => 'nullable|numeric|min:0',
        ]);

        $fields = [
            'store_name', 'store_email', 'store_phone', 'store_address',
            'active_payment_gateway', 'currency_symbol', 'currency_code',
            'store_logo_height',
            'default_details_and_fit', 'default_shipping_and_returns', 'default_garment_care',
            'social_floating_position', 'social_primary_handle', 'social_floating_tagline',
            'social_instagram', 'social_tiktok', 'social_twitter', 'social_youtube',
            'social_pinterest', 'social_facebook', 'social_whatsapp',
            'auspost_api_key', 'auspost_origin_postcode', 'auspost_default_shipping_cost',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, (string) $request->input($field));
            }
        }

        Setting::set('store_hide_brand_text', $request->boolean('store_hide_brand_text') ? '1' : '0');
        Setting::set('homepage_show_categories', $request->boolean('homepage_show_categories') ? '1' : '0');
        Setting::set('homepage_show_editorial_categories', $request->boolean('homepage_show_editorial_categories') ? '1' : '0');
        Setting::set('social_floating_enabled', $request->boolean('social_floating_enabled') ? '1' : '0');
        Setting::set('social_show_in_footer', $request->boolean('social_show_in_footer') ? '1' : '0');

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

    public function wipeData(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
            'confirmation_text' => ['required', 'string', 'in:RESET,reset,RESET STORE DATA,reset store data'],
        ], [
            'confirmation_text.in' => 'Please type "RESET" into the confirmation field to proceed.',
            'password.required' => 'Your current administrator password is required.',
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'The provided administrator password does not match our records.'])->withInput();
        }

        DB::transaction(function () use ($request) {
            Schema::disableForeignKeyConstraints();

            // Clear all catalog and customer store activity tables
            DB::table('cart_items')->delete();
            DB::table('carts')->delete();
            DB::table('order_items')->delete();
            DB::table('orders')->delete();
            DB::table('products')->delete();
            DB::table('categories')->delete();
            DB::table('hero_slides')->delete();
            DB::table('size_guides')->delete();
            DB::table('shopify_sync_logs')->delete();
            DB::table('customers')->delete();

            // Strictly preserve administrator accounts, delete any regular users
            DB::table('users')->where('is_admin', false)->orWhereNull('is_admin')->delete();

            // Optional settings reset
            if ($request->boolean('reset_settings')) {
                $preservedLogo = Setting::get('store_logo_path');
                $preservedHeight = Setting::get('store_logo_height', '70');
                $preservedName = Setting::get('store_name', 'Pistis');
                $preservedSymbol = Setting::get('currency_symbol', '$');
                $preservedCurrency = Setting::get('currency_code', 'USD');

                DB::table('settings')->delete();

                Setting::set('store_name', $preservedName);
                if ($preservedLogo) {
                    Setting::set('store_logo_path', $preservedLogo);
                    Setting::set('store_logo_height', $preservedHeight);
                }
                Setting::set('currency_symbol', $preservedSymbol);
                Setting::set('currency_code', $preservedCurrency);
                Setting::set('active_payment_gateway', 'paypal');
            }

            // Optional deletion of uploaded files
            if ($request->boolean('delete_uploaded_media')) {
                $productFiles = Storage::disk('public')->files('products');
                foreach ($productFiles as $file) {
                    Storage::disk('public')->delete($file);
                }
                $heroFiles = Storage::disk('public')->files('hero');
                foreach ($heroFiles as $file) {
                    Storage::disk('public')->delete($file);
                }
            }

            Schema::enableForeignKeyConstraints();
        });

        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {}

        return redirect()->route('admin.settings.index')->with('success', 'All website store data has been completely cleared. Administrator login accounts were preserved.');
    }
}
