<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        $activeCategorySlug = $request->input('c') ?? $request->input('category');
        $activeCategory = null;

        if ($activeCategorySlug) {
            $activeCategory = Category::where('slug', $activeCategorySlug)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($occasion = $request->input('occasion')) {
            $query->where('occasion', $occasion);
        }

        if ($fabric = $request->input('fabric')) {
            $query->where('fabric', 'like', "%{$fabric}%");
        }

        if ($color = $request->input('color')) {
            $query->where('color', 'like', "%{$color}%");
        }

        if ($priceRange = $request->input('price')) {
            if ($priceRange === 'under_3000') {
                $query->where('price', '<', 3000);
            } elseif ($priceRange === '3000_8000') {
                $query->whereBetween('price', [3000, 8000]);
            } elseif ($priceRange === '8000_20000') {
                $query->whereBetween('price', [8000, 20000]);
            } elseif ($priceRange === 'above_20000') {
                $query->where('price', '>', 20000);
            }
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('fabric', 'like', "%{$search}%")
                  ->orWhere('occasion', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'rating':
                $query->orderByDesc('rating')->orderByDesc('reviews_count');
                break;
            default:
                $query->orderByDesc('is_featured')->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->orderBy('sort_order')
            ->get();

        $title = $activeCategory ? $activeCategory->name : ($search ? "Search results for \"{$search}\"" : 'All Collections');

        $occasions = ['Wedding', 'Festival', 'Onam', 'Vishu', 'Temple', 'Reception', 'Casual'];
        $fabrics = ['Kanchipuram Silk', 'Handloom Cotton', 'Chanderi', 'Georgette', 'Cotton Silk', 'Mulberry Silk'];
        $colors = ['Ivory', 'Cream', 'Gold', 'Maroon', 'Off-White'];

        return view('storefront.shop', compact(
            'products',
            'categories',
            'activeCategory',
            'title',
            'occasions',
            'fabrics',
            'colors',
            'sort'
        ));
    }
}
