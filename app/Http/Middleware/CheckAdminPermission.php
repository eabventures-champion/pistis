<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        if (!$user->is_admin) {
            abort(403, 'Unauthorized administrative access.');
        }

        if ($user->isSuspended()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withErrors(['email' => 'Your administrative account has been suspended by the Super Admin.']);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access Denied: Insufficient permissions.'], 403);
            }

            $label = \App\Models\User::AVAILABLE_PERMISSIONS[$permission]['label'] ?? ucwords(str_replace('_', ' ', $permission));
            return redirect()->route('admin.dashboard')->with('error', "Access restricted: You do not have permission to access the {$label} section.");
        }

        return $next($request);
    }
}
