<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the admin profile and security page.
     */
    public function index()
    {
        $user = Auth::user();
        $roles = User::ROLE_PRESETS;
        $permissions = User::AVAILABLE_PERMISSIONS;

        return view('admin.profile.index', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update the administrator's profile information (name & email).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
        ]);

        return back()->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update the administrator's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.min' => 'The new password must be at least 8 characters long.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password provided is incorrect.'])
                ->withInput();
        }

        if (Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'The new password cannot be identical to your current password.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Your password has been changed successfully.');
    }

    /**
     * Invalidate other active browser sessions for this administrator.
     */
    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'confirm_password' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->confirm_password, $user->password)) {
            return back()
                ->withErrors(['confirm_password' => 'The password provided is incorrect.'])
                ->withInput();
        }

        Auth::logoutOtherDevices($request->confirm_password);

        return back()->with('success', 'All other active browser sessions have been logged out.');
    }
}
