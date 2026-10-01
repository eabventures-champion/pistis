<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SizeGuide;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->inStock();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category')) {
            $selectedCategory = Category::with('children')->findOrFail($categoryId);
            $query->whereIn('category_id', $selectedCategory->getRecursiveIds());
        }

        if ($sort = $request->input('sort')) {
            match ($sort) {
                'price_low' => $query->orderBy('price', 'asc'),
                'price_high' => $query->orderBy('price', 'desc'),
                'name' => $query->orderBy('name', 'asc'),
                'newest' => $query->latest(),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        $products = $query->paginate(12)->appends($request->query());
        $categories = Category::active()->topLevel()->with(['children.children', 'products'])->get();

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->active()->firstOrFail();

        $relatedProducts = Product::active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        $shopifyUrl = null;
        if ($product->shopify_product_id && config('services.shopify.store_url')) {
            $shopifyUrl = config('services.shopify.store_url') . '/products/' . $product->slug;
        }

        $activeSizeGuide = $product->resolveSizeGuide();
        $allSizeGuides = SizeGuide::active()->orderBy('sort_order')->orderBy('name')->get();

        return view('shop.show', compact('product', 'relatedProducts', 'shopifyUrl', 'activeSizeGuide', 'allSizeGuides'));
    }
}
