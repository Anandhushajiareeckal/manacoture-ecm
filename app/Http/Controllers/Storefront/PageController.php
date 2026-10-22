<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Page;

class PageController extends Controller
{
    public function about()
    {
        return view('storefront.about');
    }

    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->active()->firstOrFail();
        $otherPages = Page::active()->where('id', '!=', $page->id)->orderBy('sort_order')->take(6)->get();

        return view('storefront.page', compact('page', 'otherPages'));
    }

    public function account()
    {
        // Sample or authenticated customer view
        $customer = Customer::first();
        $recentOrders = Order::with('items.product')->latest()->take(5)->get();
        $wishlistCount = count(session()->get('wishlist', []));

        return view('storefront.account', compact('customer', 'recentOrders', 'wishlistCount'));
    }

    public function auth(Request $request)
    {
        $mode = $request->query('mode', 'login');
        return view('storefront.auth', compact('mode'));
    }
}
