<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInvitationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminTeamController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)
            ->orWhereNotNull('invitation_token')
            ->with('inviter')
            ->latest()
            ->get();

        $counts = [
            'total' => $admins->count(),
            'active' => $admins->where('status', 'active')->count(),
            'pending' => $admins->filter(fn($u) => $u->isPendingInvitation())->count(),
            'suspended' => $admins->where('status', 'suspended')->count(),
        ];

        $roles = User::ROLE_PRESETS;
        $permissions = User::AVAILABLE_PERMISSIONS;

        return view('admin.team.index', compact('admins', 'counts', 'roles', 'permissions'));
    }

    /**
     * Invite a new administrator by email.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|string|in:' . implode(',', array_keys(User::ROLE_PRESETS)),
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::AVAILABLE_PERMISSIONS)),
        ]);

        $role = $validated['role'];
        $isSuperAdmin = ($role === 'super_admin');

        // Determine permissions
        if ($isSuperAdmin) {
            $perms = array_keys(User::AVAILABLE_PERMISSIONS);
        } elseif ($role === 'custom') {
            $perms = $validated['permissions'] ?? [];
        } else {
            // Preset role permissions
            $presetPerms = User::ROLE_PRESETS[$role]['permissions'] ?? [];
            // Merge any manually selected permissions
            $perms = array_values(array_unique(array_merge($presetPerms, $validated['permissions'] ?? [])));
        }

        $invitationToken = Str::random(48);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make(Str::random(32)),
            'is_admin' => true,
            'is_super_admin' => $isSuperAdmin,
            'role' => $role,
            'permissions' => $perms,
            'status' => 'invited',
            'invitation_token' => $invitationToken,
            'invitation_expires_at' => Carbon::now()->addDays(7),
            'invited_by' => auth()->id(),
        ]);

        // Dispatch invitation email
        try {
            Mail::to($user->email)->send(new AdminInvitationMail($user, auth()->user()));
            $mailNotice = 'Invitation email sent successfully.';
        } catch (\Throwable $e) {
            $mailNotice = 'Admin created, but email could not be sent (check mail settings). You can copy the invite link directly.';
        }

        return redirect()->route('admin.team.index')->with('success', "Administrator {$user->name} has been invited. {$mailNotice}");
    }

    /**
     * Update an administrator's role and permissions.
     */
    public function update(Request $request, User $user)
    {
        if ($user->id === auth()->id() && !$request->has('role')) {
            return back()->with('error', 'You cannot change your own administrative role.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:' . implode(',', array_keys(User::ROLE_PRESETS)),
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', array_keys(User::AVAILABLE_PERMISSIONS)),
        ]);

        $role = $validated['role'];
        $isSuperAdmin = ($role === 'super_admin');

        if ($isSuperAdmin) {
            $perms = array_keys(User::AVAILABLE_PERMISSIONS);
        } elseif ($role === 'custom') {
            $perms = $validated['permissions'] ?? [];
        } else {
            $presetPerms = User::ROLE_PRESETS[$role]['permissions'] ?? [];
            $perms = array_values(array_unique(array_merge($presetPerms, $validated['permissions'] ?? [])));
        }

        $user->update([
            'name' => trim($validated['name']),
            'is_super_admin' => $isSuperAdmin,
            'role' => $role,
            'permissions' => $perms,
        ]);

        return redirect()->route('admin.team.index')->with('success', "Updated permissions and role for {$user->name}.");
    }

    /**
     * Resend the invitation email to a pending administrator.
     */
    public function resendInvite(User $user)
    {
        if ($user->status !== 'invited') {
            return back()->with('error', 'This user has already activated their account.');
        }

        $user->update([
            'invitation_token' => Str::random(48),
            'invitation_expires_at' => Carbon::now()->addDays(7),
        ]);

        try {
            Mail::to($user->email)->send(new AdminInvitationMail($user, auth()->user()));
            return back()->with('success', "A fresh invitation email has been sent to {$user->email}.");
        } catch (\Throwable $e) {
            return back()->with('error', "Could not send email: " . $e->getMessage());
        }
    }

    /**
     * Toggle status between active and suspended.
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        if ($user->isSuperAdmin() && User::where('is_super_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot suspend the only Super Administrator.');
        }

        if ($user->status === 'suspended') {
            $user->update(['status' => 'active']);
            $msg = "Administrator {$user->name} has been restored to active.";
        } else {
            $user->update(['status' => 'suspended']);
            $msg = "Administrator {$user->name} has been suspended. Dashboard access is blocked.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Delete an administrator account.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own administrative account.');
        }

        if ($user->isSuperAdmin() && User::where('is_super_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot delete the only Super Administrator.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.team.index')->with('success', "Administrator {$name} has been removed from the team.");
    }
}
