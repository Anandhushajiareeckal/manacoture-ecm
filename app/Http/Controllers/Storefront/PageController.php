<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

class PageController extends Controller
{
    public function about()
    {
        return view('storefront.about');
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
