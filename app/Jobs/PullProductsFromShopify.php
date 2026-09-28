<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\ShopifyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PullProductsFromShopify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 300;

    public function __construct()
    {
        $this->queue = 'shopify-sync';
    }

    public function handle(ShopifyService $shopifyService): void
    {
        if (!$shopifyService->isConfigured()) {
            return;
        }

        try {
            $shopifyProducts = $shopifyService->getProducts(['limit' => 250]);

            foreach ($shopifyProducts as $shopifyProduct) {
                $variant = $shopifyProduct['variants'][0] ?? [];

                Product::updateOrCreate(
                    ['shopify_product_id' => $shopifyProduct['id']],
                    [
                        'name' => $shopifyProduct['title'],
                        'slug' => Str::slug($shopifyProduct['title']) . '-' . $shopifyProduct['id'],
                        'description' => $shopifyProduct['body_html'] ?? '',
                        'price' => $variant['price'] ?? 0,
                        'compare_price' => $variant['compare_at_price'] ?? null,
                        'sku' => $variant['sku'] ?: ('SHOP-' . $shopifyProduct['id']),
                        'stock_quantity' => $variant['inventory_quantity'] ?? 0,
                        'shopify_variant_id' => $variant['id'] ?? null,
                        'shopify_inventory_item_id' => $variant['inventory_item_id'] ?? null,
                        'status' => $shopifyProduct['status'] === 'active' ? 'active' : 'draft',
                        'shopify_sync_enabled' => true,
                    ]
                );
            }

            Log::info('Pulled ' . count($shopifyProducts) . ' products from Shopify');
        } catch (\Exception $e) {
            Log::error('Failed to pull products from Shopify: ' . $e->getMessage());
            throw $e;
        }
    }
}
