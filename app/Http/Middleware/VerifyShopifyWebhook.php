<?php

namespace App\Http\Middleware;

use App\Services\ShopifyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyShopifyWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $hmacHeader = $request->header('X-Shopify-Hmac-Sha256');

        if (!$hmacHeader) {
            return response()->json(['error' => 'Missing HMAC header'], 401);
        }

        $shopifyService = app(ShopifyService::class);

        if (!$shopifyService->verifyWebhook($request->getContent(), $hmacHeader)) {
            return response()->json(['error' => 'Invalid HMAC signature'], 401);
        }

        return $next($request);
    }
}
