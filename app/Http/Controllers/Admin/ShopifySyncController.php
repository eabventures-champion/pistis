<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\PullProductsFromShopify;
use App\Jobs\SyncInventoryToShopify;
use App\Jobs\SyncProductToShopify;
use App\Models\Product;
use App\Models\ShopifySyncLog;
use App\Services\ShopifyService;
use Illuminate\Http\Request;

class ShopifySyncController extends Controller
{
    public function __construct(
        private ShopifyService $shopifyService
    ) {}

    /**
     * Sync a single product to Shopify
     */
    public function syncProduct(Product $product)
    {
        if (!$this->shopifyService->isConfigured()) {
            return back()->with('error', 'Shopify is not configured. Please add your API credentials in Settings.');
        }

        SyncProductToShopify::dispatch($product);

        return back()->with('success', "Product '{$product->name}' has been queued for Shopify sync.");
    }

    /**
     * Sync all syncable products
     */
    public function syncAll()
    {
        if (!$this->shopifyService->isConfigured()) {
            return back()->with('error', 'Shopify is not configured.');
        }

        $products = Product::active()->syncable()->get();
        $count = 0;

        foreach ($products as $product) {
            SyncProductToShopify::dispatch($product);
            $count++;
        }

        return back()->with('success', "{$count} products queued for Shopify sync.");
    }

    /**
     * Pull products from Shopify
     */
    public function pullFromShopify()
    {
        if (!$this->shopifyService->isConfigured()) {
            return back()->with('error', 'Shopify is not configured.');
        }

        PullProductsFromShopify::dispatch();

        return back()->with('success', 'Shopify product import has been queued.');
    }

    /**
     * View sync logs
     */
    public function logs(Request $request)
    {
        $query = ShopifySyncLog::with('product');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $logs = $query->latest()->paginate(30);

        return view('admin.shopify.logs', compact('logs'));
    }

    /**
     * Test Shopify connection
     */
    public function testConnection()
    {
        $result = $this->shopifyService->testConnection();

        if ($result['success']) {
            return back()->with('success', 'Successfully connected to Shopify store: ' . ($result['shop']['name'] ?? 'Unknown'));
        }

        return back()->with('error', 'Failed to connect: ' . ($result['error'] ?? 'Unknown error'));
    }
}
