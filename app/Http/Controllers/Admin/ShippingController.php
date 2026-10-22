<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Models\Shipment;
use App\Models\ActivityLog;

class ShippingController extends Controller
{
    public function methods()
    {
        $methods = ShippingMethod::withCount('shipments')->orderBy('sort_order')->get();
        return view('admin.shipping.methods', compact('methods'));
    }

    public function storeMethod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'base_rate' => 'required|numeric|min:0',
            'free_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);

        ShippingMethod::create($validated);
        return back()->with('success', 'Shipping method created successfully.');
    }

    public function updateMethod(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'base_rate' => 'required|numeric|min:0',
            'free_threshold' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $shippingMethod->update($validated);
        return back()->with('success', 'Shipping method updated successfully.');
    }

    public function shipments(Request $request)
    {
        $query = Shipment::with(['order.customer', 'shippingMethod']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('carrier', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $shipments = $query->latest()->paginate(15)->withQueryString();
        return view('admin.shipping.shipments', compact('shipments'));
    }

    public function updateShipment(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'carrier' => 'required|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'status' => 'required|in:pending,processing,shipped,delivered,returned,cancelled',
        ]);

        if ($validated['status'] === 'shipped' && !$shipment->shipped_at) {
            $validated['shipped_at'] = now();
        }
        if ($validated['status'] === 'delivered' && !$shipment->delivered_at) {
            $validated['delivered_at'] = now();
        }

        $shipment->update($validated);

        // sync order fulfillment status if delivered or shipped
        if (in_array($validated['status'], ['shipped', 'delivered'])) {
            $shipment->order->update(['fulfillment_status' => $validated['status']]);
        }

        ActivityLog::log('shipment.update', "Updated shipment #{$shipment->id} tracking: {$shipment->tracking_number}", $shipment);

        return back()->with('success', 'Shipment updated successfully.');
    }
}
