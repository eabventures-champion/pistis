<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        if (!$user->is_admin || !$user->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Super Administrator privilege required.'], 403);
            }

            return redirect()->route('admin.dashboard')->with('error', 'Access restricted: Only Super Administrators can manage administrators and team roles.');
        }

        return $next($request);
    }
}
