<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistIds = session()->get('wishlist', []);
        
        // If wishlist is empty, default to showing some featured/curated products for a rich patron preview
        if (empty($wishlistIds)) {
            $products = Product::active()->featured()->take(4)->get();
        } else {
            $products = Product::active()->whereIn('id', $wishlistIds)->get();
        }

        return view('storefront.wishlist', compact('products'));
    }

    public function toggle(Request $request, int $id)
    {
        $wishlist = session()->get('wishlist', []);
        $product = Product::findOrFail($id);

        if (in_array($id, $wishlist)) {
            $wishlist = array_values(array_diff($wishlist, [$id]));
            $added = false;
            $msg = "{$product->name} removed from wishlist.";
        } else {
            $wishlist[] = $id;
            $added = true;
            $msg = "{$product->name} saved to your atelier wishlist.";
        }

        session()->put('wishlist', $wishlist);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'count' => count($wishlist),
                'message' => $msg
            ]);
        }

        return back()->with('success', $msg);
    }
}
