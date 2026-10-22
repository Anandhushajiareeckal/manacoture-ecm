<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with('category')
            ->take(4)
            ->get();

        $bestSellers = Product::active()
            ->with('category')
            ->orderByDesc('rating')
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();

        $heroProduct = Product::active()->where('slug', 'aiswarya-kasavu-saree')->first() ?? Product::active()->first();

        return view('storefront.home', compact('categories', 'featuredProducts', 'bestSellers', 'heroProduct'));
    }
}
