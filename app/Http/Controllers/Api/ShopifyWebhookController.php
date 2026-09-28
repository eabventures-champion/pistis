<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopifyWebhookController extends Controller
{
    /**
     * Handle product update webhook from Shopify
     */
    public function productUpdate(Request $request)
    {
        $data = $request->all();
        $shopifyProductId = $data['id'] ?? null;

        if (!$shopifyProductId) {
            return response()->json(['error' => 'Missing product ID'], 400);
        }

        Log::info('Shopify product update webhook received', ['product_id' => $shopifyProductId]);

        $product = Product::where('shopify_product_id', $shopifyProductId)->first();

        if ($product) {
            $variant = $data['variants'][0] ?? [];

            $product->update([
                'name' => $data['title'] ?? $product->name,
                'description' => $data['body_html'] ?? $product->description,
                'price' => $variant['price'] ?? $product->price,
                'compare_price' => $variant['compare_at_price'] ?? $product->compare_price,
                'stock_quantity' => $variant['inventory_quantity'] ?? $product->stock_quantity,
            ]);

            Log::info('Product updated from Shopify webhook', ['product' => $product->name]);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle inventory level update webhook from Shopify
     */
    public function inventoryUpdate(Request $request)
    {
        $data = $request->all();
        $inventoryItemId = $data['inventory_item_id'] ?? null;
        $available = $data['available'] ?? null;

        if (!$inventoryItemId || is_null($available)) {
            return response()->json(['error' => 'Missing data'], 400);
        }

        Log::info('Shopify inventory update webhook received', [
            'inventory_item_id' => $inventoryItemId,
            'available' => $available,
        ]);

        $product = Product::where('shopify_inventory_item_id', $inventoryItemId)->first();

        if ($product) {
            $product->update(['stock_quantity' => $available]);
            Log::info('Inventory updated from Shopify webhook', [
                'product' => $product->name,
                'new_stock' => $available,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
