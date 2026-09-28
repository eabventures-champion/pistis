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

class SyncProductToShopify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 120;

    public function __construct(
        public Product $product
    ) {
        $this->queue = 'shopify-sync';
    }

    public function handle(ShopifyService $shopifyService): void
    {
        if (!$shopifyService->isConfigured()) {
            Log::info('Shopify not configured, skipping sync for product: ' . $this->product->id);
            return;
        }

        if (!$this->product->shopify_sync_enabled) {
            Log::info('Shopify sync disabled for product: ' . $this->product->id);
            return;
        }

        try {
            if ($this->product->shopify_product_id) {
                $shopifyService->updateProduct($this->product);
                Log::info('Product updated on Shopify: ' . $this->product->name);
            } else {
                $shopifyService->createProduct($this->product);
                Log::info('Product created on Shopify: ' . $this->product->name);
            }
        } catch (\Exception $e) {
            Log::error('Failed to sync product to Shopify: ' . $e->getMessage(), [
                'product_id' => $this->product->id,
            ]);
            throw $e; // Re-throw for retry
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SyncProductToShopify job permanently failed', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
