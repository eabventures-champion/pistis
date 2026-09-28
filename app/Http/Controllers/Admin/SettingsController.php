<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'Pistis'),
            'store_email' => Setting::get('store_email', ''),
            'store_phone' => Setting::get('store_phone', ''),
            'store_address' => Setting::get('store_address', ''),
            'active_payment_gateway' => Setting::get('active_payment_gateway', config('services.active_payment_gateway', 'paystack')),
            'shopify_store_url' => config('services.shopify.store_url'),
            'currency_symbol' => Setting::get('currency_symbol', '$'),
            'currency_code' => Setting::get('currency_code', 'USD'),

        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'store_name', 'store_email', 'store_phone', 'store_address',
            'active_payment_gateway', 'currency_symbol', 'currency_code',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
