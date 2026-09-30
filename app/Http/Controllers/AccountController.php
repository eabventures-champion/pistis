<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $customer = Auth::guard('customer')->user();
            if ($customer && ($customer->isDisabled() || $customer->isArchived())) {
                Auth::guard('customer')->logout();
                $msg = $customer->isDisabled()
                    ? 'Your account has been disabled. Please contact support.'
                    : 'Your account has been archived. Please contact support.';
                return redirect()->route('customer.login')->with('error', $msg);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $recentOrders = $customer->orders()->latest()->take(5)->get();

        return view('account.index', compact('customer', 'recentOrders'));
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = $customer->orders()->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(int $orderId)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->orders()->with('items.product')->findOrFail($orderId);

        return view('account.order-detail', compact('order'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
