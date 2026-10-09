<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SyncProductToShopify;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SizeGuide;
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
        $totalProducts = Product::withTrashed()->count();

        return view('admin.products.index', compact('products', 'categories', 'totalProducts'));
    }

    public function create()
    {
        $categories = Category::getIndentedList();
        $sizeGuides = SizeGuide::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'sizeGuides'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details_and_fit' => 'nullable|string',
            'shipping_and_returns' => 'nullable|string',
            'garment_care' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'size_guide_id' => 'nullable|exists:size_guides,id',
            'status' => 'required|in:active,draft,archived',
            'featured' => 'boolean',
            'shopify_sync_enabled' => 'boolean',
            'weight' => 'required|numeric|min:0.01',
            'color_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,avif|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['featured'] = $request->boolean('featured');
        $validated['shopify_sync_enabled'] = $request->boolean('shopify_sync_enabled', true);

        // Process Color Variations & Color-Specific Galleries
        $colorData = $this->processColorsAndImages($request);
        $validated['colors'] = $colorData['colors'];
        $validated['sizes'] = $this->processSizesFromRequest($request);

        // Product images are exclusively influenced and derived from Color-Specific Image Galleries
        $validated['images'] = $colorData['combined_images'];

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
        $sizeGuides = SizeGuide::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'sizeGuides'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details_and_fit' => 'nullable|string',
            'shipping_and_returns' => 'nullable|string',
            'garment_care' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'size_guide_id' => 'nullable|exists:size_guides,id',
            'status' => 'required|in:active,draft,archived',
            'featured' => 'boolean',
            'shopify_sync_enabled' => 'boolean',
            'weight' => 'required|numeric|min:0.01',
            'color_images.*.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,avif|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['featured'] = $request->boolean('featured');
        $validated['shopify_sync_enabled'] = $request->boolean('shopify_sync_enabled', true);

        // Process Color Variations & Color-Specific Galleries
        $colorData = $this->processColorsAndImages($request, $product);
        $validated['colors'] = $colorData['colors'];
        $validated['sizes'] = $this->processSizesFromRequest($request);

        // Clean up storage files for any images completely removed from color galleries
        $oldImages = is_array($product->images) ? $product->images : [];
        $newImages = $colorData['combined_images'];
        $removedImages = array_diff($oldImages, $newImages);
        foreach ($removedImages as $imgToDelete) {
            if (!str_starts_with($imgToDelete, 'http://') && !str_starts_with($imgToDelete, 'https://')) {
                if (!Product::where('id', '!=', $product->id)->where(function ($q) use ($imgToDelete) {
                    $q->where('images', 'like', "%{$imgToDelete}%")->orWhere('colors', 'like', "%{$imgToDelete}%");
                })->exists()) {
                    Storage::disk('public')->delete($imgToDelete);
                }
            }
        }

        // Product images are exclusively influenced and derived from Color-Specific Image Galleries
        $validated['images'] = $newImages;

        $product->update($validated);

        // Sync to Shopify if enabled
        if ($product->shopify_sync_enabled) {
            SyncProductToShopify::dispatch($product->fresh());
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    private function processColorsAndImages(Request $request, ?Product $existingProduct = null): array
    {
        $colorsInput = [];
        if ($request->filled('colors_json')) {
            $decoded = json_decode($request->input('colors_json'), true);
            if (is_array($decoded)) {
                $colorsInput = $decoded;
            }
        } elseif ($request->has('colors') && is_array($request->input('colors'))) {
            $colorsInput = $request->input('colors');
        }

        $combinedImages = [];
        $processedColors = [];

        foreach ($colorsInput as $index => $c) {
            if (!is_array($c)) {
                if (is_string($c) && trim($c) !== '') {
                    $c = ['name' => trim($c)];
                } else {
                    continue;
                }
            }

            $name = trim($c['name'] ?? '');
            if ($name === '') continue;

            $code = trim($c['code'] ?? '');
            if ($code === '' || $code === '#') {
                $code = Product::defaultHexForColorName($name);
            }

            // Existing images for this color
            $colorImages = [];
            if (!empty($c['existing_images']) && is_array($c['existing_images'])) {
                $colorImages = array_values(array_filter($c['existing_images']));
            } elseif (!empty($c['images']) && is_array($c['images'])) {
                $colorImages = array_values(array_filter($c['images']));
            }

            // Handle new file uploads for this color (check index, name, or alternate formats)
            $uploadedFiles = $request->file("color_images.{$index}") 
                ?? $request->file("color_images.{$name}")
                ?? $request->file("color_images_{$index}")
                ?? [];

            if ($uploadedFiles) {
                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }
                foreach ($uploadedFiles as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('products', 'public');
                        $colorImages[] = $path;
                    }
                }
            }

            $colorPrice = null;
            if (isset($c['price']) && $c['price'] !== '' && is_numeric($c['price']) && (float) $c['price'] >= 0) {
                $colorPrice = round((float) $c['price'], 2);
            }

            $colorComparePrice = null;
            if (isset($c['compare_price']) && $c['compare_price'] !== '' && is_numeric($c['compare_price']) && (float) $c['compare_price'] >= 0) {
                $colorComparePrice = round((float) $c['compare_price'], 2);
            }

            $processedColors[] = [
                'name' => $name,
                'code' => $code,
                'price' => $colorPrice,
                'compare_price' => $colorComparePrice,
                'images' => $colorImages,
            ];

            foreach ($colorImages as $img) {
                if (!in_array($img, $combinedImages)) {
                    $combinedImages[] = $img;
                }
            }
        }

        return [
            'colors' => !empty($processedColors) ? $processedColors : null,
            'combined_images' => $combinedImages,
        ];
    }

    private function processSizesFromRequest(Request $request): ?array
    {
        $sizes = [];
        if ($request->filled('sizes_json')) {
            $decoded = json_decode($request->input('sizes_json'), true);
            if (is_array($decoded)) {
                $sizes = $decoded;
            }
        } elseif ($request->has('sizes') && is_array($request->input('sizes'))) {
            $sizes = $request->input('sizes');
        }

        $clean = [];
        foreach ($sizes as $s) {
            $s = trim((string) $s);
            if ($s !== '' && !in_array($s, $clean)) {
                $clean[] = $s;
            }
        }

        return !empty($clean) ? $clean : null;
    }

    public function destroy(Product $product)
    {
        CartItem::where('product_id', $product->id)->delete();
        OrderItem::where('product_id', $product->id)->update(['product_id' => null]);
        $product->forceDelete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function destroyAll(Request $request)
    {
        $count = Product::withTrashed()->count();
        if ($count === 0) {
            return back()->with('info', 'There are no products to delete.');
        }

        CartItem::query()->delete();
        OrderItem::whereNotNull('product_id')->update(['product_id' => null]);
        Product::withTrashed()->forceDelete();

        return redirect()->route('admin.products.index')
            ->with('success', "All {$count} product(s) have been permanently deleted.");
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Please select at least one product.');
        }

        switch ($action) {
            case 'delete':
                $count = count($ids);
                CartItem::whereIn('product_id', $ids)->delete();
                OrderItem::whereIn('product_id', $ids)->update(['product_id' => null]);
                Product::withTrashed()->whereIn('id', $ids)->forceDelete();
                return back()->with('success', "{$count} selected product(s) permanently deleted.");

            case 'activate':
                Product::withTrashed()->whereIn('id', $ids)->update(['status' => 'active', 'deleted_at' => null]);
                return back()->with('success', count($ids) . ' product(s) marked as active.');

            case 'draft':
                Product::withTrashed()->whereIn('id', $ids)->update(['status' => 'draft']);
                return back()->with('success', count($ids) . ' product(s) marked as draft.');

            case 'archive':
                Product::withTrashed()->whereIn('id', $ids)->update(['status' => 'archived']);
                return back()->with('success', count($ids) . ' product(s) archived.');

            default:
                return back()->with('error', 'Invalid action selected.');
        }
    }
}
