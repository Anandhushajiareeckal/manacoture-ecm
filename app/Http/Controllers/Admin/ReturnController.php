<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\ActivityLog;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReturn::with(['order.customer', 'items.orderItem']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $returns = $query->latest()->paginate(15)->withQueryString();
        return view('admin.returns.index', compact('returns'));
    }

    public function show(OrderReturn $orderReturn)
    {
        $orderReturn->load(['order.customer', 'items.orderItem.product', 'processor']);
        return view('admin.returns.show', compact('orderReturn'));
    }

    public function updateStatus(Request $request, OrderReturn $orderReturn)
    {
        $validated = $request->validate([
            'status' => 'required|in:requested,approved,rejected,received,refunded',
            'admin_notes' => 'nullable|string',
            'restock' => 'boolean',
        ]);

        $orderReturn->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'processed_by' => auth()->id(),
        ]);

        // Restock inventory if requested and not yet restocked
        if ($request->boolean('restock') && !$orderReturn->restocked && in_array($validated['status'], ['received', 'refunded'])) {
            foreach ($orderReturn->items as $retItem) {
                if ($retItem->orderItem && $retItem->orderItem->product_id) {
                    $prod = Product::find($retItem->orderItem->product_id);
                    if ($prod) {
                        $before = $prod->stock_qty;
                        $prod->increment('stock_qty', $retItem->qty);
                        InventoryMovement::create([
                            'product_id' => $prod->id,
                            'type' => 'return',
                            'qty' => $retItem->qty,
                            'stock_before' => $before,
                            'stock_after' => $prod->fresh()->stock_qty,
                            'reason' => 'Customer Return Restocked',
                            'reference' => $orderReturn->return_number,
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }
            $orderReturn->update(['restocked' => true]);
        }

        ActivityLog::log('return.update', "Updated return {$orderReturn->return_number} to {$validated['status']}", $orderReturn);

        return back()->with('success', "Return {$orderReturn->return_number} updated.");
    }
}
