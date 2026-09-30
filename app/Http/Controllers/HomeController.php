<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::active()
            ->ordered()
            ->get();

        // Editorial Selection: Load featured & in-stock products with full category hierarchy
        $featuredProducts = Product::active()
            ->featured()
            ->inStock()
            ->with(['category.parent.parent.parent'])
            ->latest()
            ->take(32)
            ->get();

        // Ensure rich selection across all categories
        if ($featuredProducts->count() < 8) {
            $existingIds = $featuredProducts->pluck('id');
            $additional = Product::active()
                ->inStock()
                ->whereNotIn('id', $existingIds)
                ->with(['category.parent.parent.parent'])
                ->latest()
                ->take(16)
                ->get();
            $featuredProducts = $featuredProducts->concat($additional);
        }

        // Active parent categories for badges filter next to Editorial Selection
        $parentCategories = Category::active()
            ->topLevel()
            ->orderBy('sort_order')
            ->get();

        $categories = Category::active()
            ->topLevel()
            ->with(['children.children', 'products'])
            ->get();

        $newArrivals = Product::active()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('heroSlides', 'featuredProducts', 'categories', 'parentCategories', 'newArrivals'));
    }
}
