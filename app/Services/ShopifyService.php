<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ShopifySyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    private string $storeUrl;
    private string $accessToken;
    private string $apiVersion;
    private string $apiSecret;

    public function __construct()
    {
        $this->storeUrl = rtrim(config('services.shopify.store_url', ''), '/');
        $this->accessToken = config('services.shopify.access_token', '');
        $this->apiVersion = config('services.shopify.api_version', '2025-04');
        $this->apiSecret = config('services.shopify.api_secret', '');
    }

    /**
     * Check if Shopify is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->storeUrl) && !empty($this->accessToken);
    }

    /**
     * Test the connection to Shopify
     */
    public function testConnection(): array
    {
        try {
            $response = $this->request('GET', '/shop.json');
            return ['success' => true, 'shop' => $response['shop'] ?? []];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ─── Product Operations ─────────────────────────────────────────

    /**
     * Create a product on Shopify
     */
    public function createProduct(Product $product): array
    {
        $payload = $this->buildProductPayload($product);

        $log = $this->createLog($product, 'push', $payload);

        try {
            $response = $this->request('POST', '/products.json', ['product' => $payload]);

            $shopifyProduct = $response['product'] ?? [];

            // Store Shopify IDs back on the product
            $product->update([
                'shopify_product_id' => $shopifyProduct['id'] ?? null,
                'shopify_variant_id' => $shopifyProduct['variants'][0]['id'] ?? null,
                'shopify_inventory_item_id' => $shopifyProduct['variants'][0]['inventory_item_id'] ?? null,
            ]);

            $log->update([
                'status' => 'success',
                'response_payload' => $shopifyProduct,
            ]);

            return $shopifyProduct;
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update a product on Shopify
     */
    public function updateProduct(Product $product): array
    {
        if (!$product->shopify_product_id) {
            return $this->createProduct($product);
        }

        $payload = $this->buildProductPayload($product);
        $log = $this->createLog($product, 'push', $payload);

        try {
            $response = $this->request(
                'PUT',
                "/products/{$product->shopify_product_id}.json",
                ['product' => array_merge(['id' => $product->shopify_product_id], $payload)]
            );

            $shopifyProduct = $response['product'] ?? [];

            $log->update([
                'status' => 'success',
                'response_payload' => $shopifyProduct,
            ]);

            return $shopifyProduct;
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a product from Shopify
     */
    public function deleteProduct(int $shopifyProductId): bool
    {
        $log = $this->createLog(null, 'push', ['shopify_product_id' => $shopifyProductId]);

        try {
            $this->request('DELETE', "/products/{$shopifyProductId}.json");
            $log->update(['status' => 'success']);
            return true;
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get a single product from Shopify
     */
    public function getProduct(int $shopifyProductId): array
    {
        $response = $this->request('GET', "/products/{$shopifyProductId}.json");
        return $response['product'] ?? [];
    }

    /**
     * Get all products from Shopify
     */
    public function getProducts(array $params = []): array
    {
        $response = $this->request('GET', '/products.json', $params);
        return $response['products'] ?? [];
    }

    // ─── Inventory Operations ───────────────────────────────────────

    /**
     * Get locations from Shopify
     */
    public function getLocations(): array
    {
        $response = $this->request('GET', '/locations.json');
        return $response['locations'] ?? [];
    }

    /**
     * Set inventory level on Shopify
     */
    public function setInventoryLevel(int $inventoryItemId, int $locationId, int $quantity): array
    {
        $payload = [
            'location_id' => $locationId,
            'inventory_item_id' => $inventoryItemId,
            'available' => $quantity,
        ];

        $log = $this->createLog(null, 'inventory_update', $payload);

        try {
            $response = $this->request('POST', '/inventory_levels/set.json', $payload);

            $log->update([
                'status' => 'success',
                'response_payload' => $response,
            ]);

            return $response['inventory_level'] ?? [];
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get inventory levels for an item
     */
    public function getInventoryLevels(int $inventoryItemId): array
    {
        $response = $this->request('GET', '/inventory_levels.json', [
            'inventory_item_ids' => $inventoryItemId,
        ]);
        return $response['inventory_levels'] ?? [];
    }

    // ─── Webhook Operations ─────────────────────────────────────────

    /**
     * Register a webhook with Shopify
     */
    public function registerWebhook(string $topic, string $address): array
    {
        $response = $this->request('POST', '/webhooks.json', [
            'webhook' => [
                'topic' => $topic,
                'address' => $address,
                'format' => 'json',
            ],
        ]);
        return $response['webhook'] ?? [];
    }

    /**
     * List all webhooks
     */
    public function listWebhooks(): array
    {
        $response = $this->request('GET', '/webhooks.json');
        return $response['webhooks'] ?? [];
    }

    /**
     * Verify a Shopify webhook HMAC signature
     */
    public function verifyWebhook(string $data, string $hmacHeader): bool
    {
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, $this->apiSecret, true));
        return hash_equals($calculatedHmac, $hmacHeader);
    }

    // ─── Helpers ────────────────────────────────────────────────────

    /**
     * Build product payload for Shopify API
     */
    private function buildProductPayload(Product $product): array
    {
        $payload = [
            'title' => $product->name,
            'body_html' => $product->description,
            'product_type' => $product->category?->name ?? '',
            'status' => $product->status === 'active' ? 'active' : 'draft',
            'variants' => [
                [
                    'price' => $product->price,
                    'sku' => $product->sku,
                    'inventory_management' => 'shopify',
                    'inventory_quantity' => $product->stock_quantity,
                ],
            ],
        ];

        if ($product->compare_price) {
            $payload['variants'][0]['compare_at_price'] = $product->compare_price;
        }

        if ($product->weight) {
            $payload['variants'][0]['weight'] = $product->weight;
            $payload['variants'][0]['weight_unit'] = 'kg';
        }

        // Add images if available
        if ($product->images && count($product->images) > 0) {
            $payload['images'] = [];
            foreach ($product->images as $imageUrl) {
                $payload['images'][] = ['src' => url('storage/' . $imageUrl)];
            }
        }

        return $payload;
    }

    /**
     * Make an API request to Shopify
     */
    private function request(string $method, string $endpoint, array $data = []): array
    {
        $url = "{$this->storeUrl}/admin/api/{$this->apiVersion}" . $endpoint;

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
            'Content-Type' => 'application/json',
        ])->retry(3, 1000, function ($exception) {
            // Retry on rate limit (429) and server errors (5xx)
            return $exception instanceof \Illuminate\Http\Client\RequestException
                && in_array($exception->response->status(), [429, 500, 502, 503, 504]);
        })->{strtolower($method)}($url, $data);

        if ($response->failed()) {
            $errorBody = $response->json();
            $errorMessage = $errorBody['errors'] ?? $response->body();
            if (is_array($errorMessage)) {
                $errorMessage = json_encode($errorMessage);
            }

            Log::error('Shopify API Error', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            throw new \Exception("Shopify API Error ({$response->status()}): {$errorMessage}");
        }

        return $response->json() ?? [];
    }

    /**
     * Create a sync log entry
     */
    private function createLog(?Product $product, string $action, array $payload): ShopifySyncLog
    {
        return ShopifySyncLog::create([
            'product_id' => $product?->id,
            'action' => $action,
            'status' => 'pending',
            'request_payload' => $payload,
        ]);
    }
}
