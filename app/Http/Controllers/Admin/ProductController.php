<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncProductToShopify;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::getIndentedList();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,draft,archived',
            'featured' => 'boolean',
            'shopify_sync_enabled' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,avif|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['featured'] = $request->boolean('featured');
        $validated['shopify_sync_enabled'] = $request->boolean('shopify_sync_enabled', true);
        $validated['colors'] = $this->extractColorsFromRequest($request);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    $path = $image->store('products', 'public');
                    $imagePaths[] = $path;
                }
            }
        }
        $validated['images'] = $imagePaths;

        $product = Product::create($validated);

        // Dispatch Shopify sync if enabled
        if ($product->shopify_sync_enabled) {
            SyncProductToShopify::dispatch($product);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::getIndentedList();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,draft,archived',
            'featured' => 'boolean',
            'shopify_sync_enabled' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,avif|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['featured'] = $request->boolean('featured');
        $validated['shopify_sync_enabled'] = $request->boolean('shopify_sync_enabled', true);
        $validated['colors'] = $this->extractColorsFromRequest($request);

        // Handle existing images and removals
        $existingImages = is_array($product->images) ? $product->images : [];

        if ($request->has('remove_images')) {
            $removeIndices = (array) $request->input('remove_images');
            foreach ($removeIndices as $index) {
                if (isset($existingImages[$index])) {
                    $imageToDelete = $existingImages[$index];
                    // Only delete if it is a local storage path
                    if (!str_starts_with($imageToDelete, 'http://') && !str_starts_with($imageToDelete, 'https://')) {
                        Storage::disk('public')->delete($imageToDelete);
                    }
                    unset($existingImages[$index]);
                }
            }
            $existingImages = array_values($existingImages);
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    $path = $image->store('products', 'public');
                    $existingImages[] = $path;
                }
            }
        }

        $validated['images'] = $existingImages;

        $product->update($validated);

        // Sync to Shopify if enabled
        if ($product->shopify_sync_enabled) {
            SyncProductToShopify::dispatch($product->fresh());
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    private function extractColorsFromRequest(Request $request): ?array
    {
        $colors = [];
        if ($request->filled('colors_json')) {
            $decoded = json_decode($request->input('colors_json'), true);
            if (is_array($decoded)) {
                $colors = $decoded;
            }
        } elseif ($request->has('colors') && is_array($request->input('colors'))) {
            $colors = $request->input('colors');
        }

        $processedColors = [];
        foreach ($colors as $c) {
            if (is_array($c)) {
                $name = trim($c['name'] ?? '');
                if ($name !== '') {
                    $code = trim($c['code'] ?? '');
                    if ($code === '' || $code === '#') {
                        $code = Product::defaultHexForColorName($name);
                    }
                    $processedColors[] = [
                        'name' => $name,
                        'code' => $code,
                    ];
                }
            } elseif (is_string($c)) {
                $name = trim($c);
                if ($name !== '') {
                    $processedColors[] = [
                        'name' => $name,
                        'code' => Product::defaultHexForColorName($name),
                    ];
                }
            }
        }

        return !empty($processedColors) ? $processedColors : null;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
