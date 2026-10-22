<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $itemTotal = $product->price * $item['qty'];
                $subtotal += $itemTotal;
                $items[] = [
                    'key' => $key,
                    'product' => $product,
                    'qty' => $item['qty'],
                    'size' => $item['size'] ?? 'Free',
                    'color' => $item['color'] ?? $product->color,
                    'total' => $itemTotal,
                ];
            }
        }

        $freeShippingThreshold = 5000;
        $shipping = ($subtotal >= $freeShippingThreshold || empty($items)) ? 0 : 250;
        
        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon && $subtotal > 0) {
            if ($coupon['type'] === 'percent') {
                $discount = ($subtotal * $coupon['value']) / 100;
            } else {
                $discount = min($subtotal, $coupon['value']);
            }
        }

        $total = max(0, $subtotal + $shipping - $discount);

        return view('storefront.cart', compact('items', 'subtotal', 'shipping', 'discount', 'total', 'coupon', 'freeShippingThreshold'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $size = $request->input('size', 'Free');
        $color = $request->input('color', $product->color ?? 'Ivory & Gold');
        $key = $product->id . '-' . md5($size . '-' . $color);

        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['qty'] += (int)$request->input('qty', 1);
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'qty' => (int)$request->input('qty', 1),
                'size' => $size,
                'color' => $color,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            $count = array_sum(array_column($cart, 'qty'));
            return response()->json(['success' => true, 'count' => $count, 'message' => "{$product->name} added to your bag."]);
        }

        return redirect()->route('cart.index')->with('success', "{$product->name} added to your bag.");
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'qty' => 'required|integer|min:0',
        ]);

        $cart = session()->get('cart', []);
        $key = $request->key;
        $qty = (int)$request->qty;

        if (isset($cart[$key])) {
            if ($qty <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['qty'] = $qty;
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Bag updated successfully.');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from bag.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $code = strtoupper(trim($request->code));

        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $p = Product::find($item['product_id']);
            if ($p) $subtotal += $p->price * $item['qty'];
        }

        if ($coupon->min_order && $subtotal < $coupon->min_order) {
            return back()->with('error', "This coupon requires a minimum purchase of ₹" . number_format($coupon->min_order));
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float)$coupon->value,
            'description' => $coupon->description,
        ]);

        return back()->with('success', "Coupon {$coupon->code} applied successfully!");
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }
}
