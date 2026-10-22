<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use App\Models\InventoryMovement;
use App\Models\Coupon;
use App\Models\ActivityLog;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('info', 'Your bag is empty.');
        }

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

        $shippingMethods = ShippingMethod::where('is_active', true)->orderBy('base_rate')->get();
        $defaultShipping = $subtotal >= 5000 ? 0 : ($shippingMethods->first()?->base_rate ?? 0);

        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon && $subtotal > 0) {
            if ($coupon['type'] === 'percent') {
                $discount = ($subtotal * $coupon['value']) / 100;
            } else {
                $discount = min($subtotal, $coupon['value']);
            }
        }

        $total = max(0, $subtotal + $defaultShipping - $discount);

        return view('storefront.checkout', compact('items', 'subtotal', 'shippingMethods', 'defaultShipping', 'discount', 'total', 'coupon'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your shopping bag is empty.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:2',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
            'payment_method' => 'required|string|in:card,upi,netbanking,cod',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['email' => $validated['email']],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'is_active' => true,
            ]
        );

        // Record customer address
        CustomerAddress::create([
            'customer_id' => $customer->id,
            'type' => 'shipping',
            'full_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'] ?? 'IN',
            'phone' => $validated['phone'],
            'is_default' => true,
        ]);

        // Calculate totals
        $subtotal = 0;
        $orderItemsData = [];
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $lineTotal = $product->price * $item['qty'];
                $subtotal += $lineTotal;
                $orderItemsData[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                    'variant_title' => ($item['size'] ?? 'Free') . ' / ' . ($item['color'] ?? $product->color),
                ];
            }
        }

        $shippingMethod = null;
        if (!empty($validated['shipping_method_id'])) {
            $shippingMethod = ShippingMethod::find($validated['shipping_method_id']);
        }

        $shippingTotal = ($subtotal >= 5000) ? 0 : ($shippingMethod ? $shippingMethod->base_rate : 0);

        $discountTotal = 0;
        $couponId = null;
        $couponSession = session()->get('coupon');
        if ($couponSession) {
            $couponModel = Coupon::where('code', $couponSession['code'])->first();
            if ($couponModel) {
                $couponId = $couponModel->id;
                if ($couponModel->type === 'percent') {
                    $discountTotal = ($subtotal * $couponModel->value) / 100;
                } else {
                    $discountTotal = min($subtotal, $couponModel->value);
                }
                $couponModel->increment('used_count');
            }
        }

        $grandTotal = max(0, $subtotal + $shippingTotal - $discountTotal);
        $orderNumber = 'MC-ORD-' . strtoupper(Str::random(8));

        // Create Order
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'coupon_id' => $couponId,
            'shipping_method_id' => $shippingMethod?->id,
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'shipping_total' => $shippingTotal,
            'tax_total' => 0,
            'grand_total' => $grandTotal,
            'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'paid',
            'fulfillment_status' => 'processing',
            'shipping_address' => [
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'] ?? 'IN',
                'phone' => $validated['phone'],
            ],
            'customer_notes' => $validated['customer_notes'] ?? null,
        ]);

        // Create Order Items and decrease stock
        foreach ($orderItemsData as $d) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $d['product']->id,
                'product_name' => $d['product']->name,
                'product_sku' => $d['product']->sku ?? ('SKU-' . $d['product']->id),
                'variant_attributes' => $d['variant_title'] ? ['size' => $d['variant_title']] : null,
                'unit_price' => $d['unit_price'],
                'qty' => $d['qty'],
                'line_total' => $d['line_total'],
                'tax_amount' => 0,
            ]);

            // Decrement product inventory
            $oldStock = $d['product']->stock_qty;
            $newStock = max(0, $oldStock - $d['qty']);
            $d['product']->update(['stock_qty' => $newStock]);

            InventoryMovement::create([
                'product_id' => $d['product']->id,
                'type' => 'stock_out',
                'qty' => $d['qty'],
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'reason' => 'Storefront Order Checkout #' . $order->order_number,
                'reference' => $order->order_number,
            ]);
        }

        ActivityLog::log('order.created', "New storefront order placed: #{$order->order_number} (₹" . number_format($order->grand_total, 2) . ")", $order);

        // Clear cart
        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('orders.confirmation', $order->order_number);
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with(['customer', 'items.product'])->firstOrFail();
        return view('storefront.order-confirmation', compact('order'));
    }
}
