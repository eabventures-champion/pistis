<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customer = Auth::guard('customer')->user();

        if ($customer && ($customer->isDisabled() || $customer->isArchived())) {
            Auth::guard('customer')->logout();

            $msg = $customer->isDisabled()
                ? 'Your account has been disabled. Please contact customer support.'
                : 'Your account is archived. Please contact customer support.';

            return redirect()->route('customer.login')->with('error', $msg);
        }

        return $next($request);
    }
}
