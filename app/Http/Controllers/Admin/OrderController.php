<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\ShippingMethod;
use App\Models\InventoryMovement;
use App\Models\Setting;
use App\Models\ActivityLog;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('fulfillment_status')) {
            $query->where('fulfillment_status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Status counts for tabs
        $allCount = Order::count();
        $pendingCount = Order::where('fulfillment_status', 'pending')->count();
        $processingCount = Order::where('fulfillment_status', 'processing')->count();
        $shippedCount = Order::where('fulfillment_status', 'shipped')->count();
        $deliveredCount = Order::where('fulfillment_status', 'delivered')->count();
        $cancelledCount = Order::where('fulfillment_status', 'cancelled')->count();

        return view('admin.orders.index', compact(
            'orders',
            'allCount',
            'pendingCount',
            'processingCount',
            'shippedCount',
            'deliveredCount',
            'cancelledCount'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['customer.addresses', 'items.product', 'statusHistory.user', 'payments', 'shipments.shippingMethod', 'coupon']);
        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        return view('admin.orders.show', compact('order', 'shippingMethods'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('status', 'active')->where('stock_qty', '>', 0)->get();
        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        return view('admin.orders.create', compact('customers', 'products', 'shippingMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'payment_status' => 'required|in:pending,paid',
            'fulfillment_status' => 'required|in:pending,processing,shipped,delivered',
            'notes' => 'nullable|string',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        $address = $customer->addresses()->where('type', 'shipping')->first();

        $subtotal = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $qty = (int)$item['qty'];
            $lineTotal = (float)$product->price * $qty;
            $subtotal += $lineTotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'qty' => $qty,
                'unit_price' => $product->price,
                'line_total' => $lineTotal,
                'tax_amount' => round($lineTotal * 0.0825, 2),
            ];
        }

        $shippingCost = 0.00;
        if (!empty($validated['shipping_method_id'])) {
            $sm = ShippingMethod::find($validated['shipping_method_id']);
            if ($sm) {
                $shippingCost = ($sm->free_threshold && $subtotal >= $sm->free_threshold) ? 0.00 : (float)$sm->base_rate;
            }
        }

        $taxTotal = round($subtotal * 0.0825, 2);
        $grandTotal = round($subtotal + $shippingCost + $taxTotal, 2);

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'billing_address' => [
                'full_name' => $customer->full_name,
                'address_line1' => $address?->address_line1 ?? 'Address not on file',
                'city' => $address?->city ?? 'City',
                'state' => $address?->state ?? 'State',
                'postal_code' => $address?->postal_code ?? '00000',
                'country' => $address?->country ?? 'US',
                'phone' => $customer->phone,
            ],
            'shipping_address' => [
                'full_name' => $customer->full_name,
                'address_line1' => $address?->address_line1 ?? 'Address not on file',
                'city' => $address?->city ?? 'City',
                'state' => $address?->state ?? 'State',
                'postal_code' => $address?->postal_code ?? '00000',
                'country' => $address?->country ?? 'US',
                'phone' => $customer->phone,
            ],
            'subtotal' => $subtotal,
            'discount_total' => 0.00,
            'shipping_total' => $shippingCost,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
            'payment_status' => $validated['payment_status'],
            'fulfillment_status' => $validated['fulfillment_status'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
        ]);

        foreach ($orderItems as $itemData) {
            $order->items()->create($itemData);

            // Deduct stock
            $prod = Product::find($itemData['product_id']);
            if ($prod) {
                $before = $prod->stock_qty;
                $prod->decrement('stock_qty', $itemData['qty']);
                InventoryMovement::create([
                    'product_id' => $prod->id,
                    'type' => 'sale',
                    'qty' => -$itemData['qty'],
                    'stock_before' => $before,
                    'stock_after' => $prod->fresh()->stock_qty,
                    'reason' => 'Admin Manual Order Placement',
                    'reference' => $order->order_number,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        // Record Initial History
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'Order Created',
            'type' => 'fulfillment',
            'note' => 'Order manually created by administrator.',
            'user_id' => auth()->id(),
        ]);

        if ($order->payment_status === 'paid') {
            Payment::create([
                'order_id' => $order->id,
                'method' => $order->payment_method,
                'amount' => $order->grand_total,
                'currency' => 'USD',
                'reference' => 'manual_' . Str::random(10),
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'Payment Marked as Paid',
                'type' => 'payment',
                'note' => 'Payment manually verified by admin.',
                'user_id' => auth()->id(),
            ]);
        }

        ActivityLog::log('order.create', "Created order #{$order->order_number} for {$customer->full_name}", $order);

        return redirect()->route('admin.orders.show', $order)->with('success', "Order #{$order->order_number} created successfully.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'fulfillment_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,refunded,failed',
            'note' => 'nullable|string|max:500',
        ]);

        $changed = false;

        if (!empty($validated['fulfillment_status']) && $validated['fulfillment_status'] !== $order->fulfillment_status) {
            $oldStatus = $order->fulfillment_status;
            $newStatus = $validated['fulfillment_status'];
            $order->update(['fulfillment_status' => $newStatus]);
            $changed = true;

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => ucfirst($newStatus),
                'type' => 'fulfillment',
                'note' => $validated['note'] ?: "Status updated from {$oldStatus} to {$newStatus}",
                'user_id' => auth()->id(),
            ]);
        }

        if (!empty($validated['payment_status']) && $validated['payment_status'] !== $order->payment_status) {
            $oldP = $order->payment_status;
            $newP = $validated['payment_status'];
            $order->update(['payment_status' => $newP]);
            $changed = true;

            if ($newP === 'paid' && !$order->payments()->where('status', 'paid')->exists()) {
                Payment::create([
                    'order_id' => $order->id,
                    'method' => $order->payment_method ?: 'Credit Card',
                    'amount' => $order->grand_total,
                    'currency' => 'USD',
                    'reference' => 'ch_' . Str::random(12),
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'Payment: ' . ucfirst($newP),
                'type' => 'payment',
                'note' => $validated['note'] ?: "Payment status updated from {$oldP} to {$newP}",
                'user_id' => auth()->id(),
            ]);
        }

        if ($changed) {
            ActivityLog::log('order.status_update', "Updated status for order #{$order->order_number}", $order);
            return back()->with('success', 'Order status updated successfully.');
        }

        return back()->with('info', 'No changes detected.');
    }

    public function invoice(Order $order)
    {
        $order->load(['customer', 'items.product', 'payments', 'shipments']);
        $storeName = Setting::get('store_name', 'INSPINIA Commerce');
        $storeEmail = Setting::get('store_email', 'support@inspinia.com');
        $storePhone = Setting::get('store_phone', '+1 (555) 789-0123');
        $storeAddress = Setting::get('store_address', '742 Evergreen Terrace, Springfield, OR 97477');

        return view('admin.orders.invoice', compact('order', 'storeName', 'storeEmail', 'storePhone', 'storeAddress'));
    }
}
