<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminInvitationController extends Controller
{
    /**
     * Show the invitation acceptance / password setup screen.
     */
    public function showAccept(string $token)
    {
        $user = User::where('invitation_token', $token)->first();

        if (!$user) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This invitation link is invalid or has already been used.',
            ]);
        }

        if ($user->invitation_expires_at && $user->invitation_expires_at->isPast()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This invitation link has expired. Please ask the Super Administrator to resend an invite.',
            ]);
        }

        return view('auth.admin-accept-invitation', compact('user', 'token'));
    }

    /**
     * Process password setup and activate the administrative account.
     */
    public function processAccept(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)->first();

        if (!$user) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This invitation link is invalid or has already been used.',
            ]);
        }

        if ($user->invitation_expires_at && $user->invitation_expires_at->isPast()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This invitation link has expired. Please ask the Super Administrator to resend an invite.',
            ]);
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'invitation_token' => null,
            'invitation_expires_at' => null,
            'invitation_accepted_at' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', "Welcome to the team, {$user->name}! Your administrative account is now active.");
    }
}
