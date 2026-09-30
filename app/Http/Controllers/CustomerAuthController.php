<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $attemptCredentials = array_merge($credentials, ['archived_at' => null, 'is_disabled' => false]);

        if (Auth::guard('customer')->attempt($attemptCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('account.index'));
        }

        // Check if customer account exists and is disabled or archived
        $customer = Customer::where('email', $credentials['email'])->first();
        if ($customer && Hash::check($credentials['password'], $customer->password)) {
            if ($customer->isDisabled()) {
                return back()->withErrors([
                    'email' => 'This account has been disabled. Account access and purchases are blocked. Please contact support.',
                ])->onlyInput('email');
            }
            if ($customer->isArchived()) {
                return back()->withErrors([
                    'email' => 'This customer account has been archived. Please contact support.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $customer = Customer::create($validated);

        Auth::guard('customer')->login($customer);

        return redirect()->route('account.index')
            ->with('success', 'Account created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
