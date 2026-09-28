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

class SyncInventoryToShopify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    public int $timeout = 60;

    public function __construct(
        public Product $product
    ) {
        $this->queue = 'shopify-sync';
    }

    public function handle(ShopifyService $shopifyService): void
    {
        if (!$shopifyService->isConfigured()) {
            return;
        }

        if (!$this->product->shopify_sync_enabled || !$this->product->shopify_inventory_item_id) {
            return;
        }

        try {
            // Get the first location
            $locations = $shopifyService->getLocations();
            if (empty($locations)) {
                Log::warning('No Shopify locations found for inventory sync');
                return;
            }

            $locationId = $locations[0]['id'];

            $shopifyService->setInventoryLevel(
                $this->product->shopify_inventory_item_id,
                $locationId,
                $this->product->stock_quantity
            );

            Log::info('Inventory synced to Shopify', [
                'product' => $this->product->name,
                'quantity' => $this->product->stock_quantity,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to sync inventory to Shopify: ' . $e->getMessage(), [
                'product_id' => $this->product->id,
            ]);
            throw $e;
        }
    }
}
