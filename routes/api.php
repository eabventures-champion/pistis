<?php

use App\Http\Controllers\Api\ShopifyWebhookController;
use Illuminate\Support\Facades\Route;

// Shopify Webhooks (with HMAC verification)
Route::middleware('shopify.webhook')->prefix('webhooks/shopify')->group(function () {
    Route::post('/products-update', [ShopifyWebhookController::class, 'productUpdate']);
    Route::post('/inventory-update', [ShopifyWebhookController::class, 'inventoryUpdate']);
});
