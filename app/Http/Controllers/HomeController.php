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

        $featuredProducts = Product::active()
            ->featured()
            ->inStock()
            ->latest()
            ->take(8)
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

        return view('home', compact('heroSlides', 'featuredProducts', 'categories', 'newArrivals'));
    }
}
