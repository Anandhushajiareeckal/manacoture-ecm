<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\ActivityLog;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($filter = $request->input('filter')) {
            if ($filter === 'low') {
                $query->whereColumn('stock_qty', '<=', 'low_stock_threshold')->where('stock_qty', '>', 0);
            } elseif ($filter === 'out') {
                $query->where('stock_qty', '<=', 0);
            } elseif ($filter === 'in') {
                $query->whereColumn('stock_qty', '>', 'low_stock_threshold');
            }
        }

        $products = $query->orderBy('stock_qty')->paginate(15)->withQueryString();

        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock_qty', '<=', 'low_stock_threshold')->where('stock_qty', '>', 0)->count();
        $outOfStockCount = Product::where('stock_qty', '<=', 0)->count();
        $totalInventoryUnits = Product::sum('stock_qty');

        return view('admin.inventory.index', compact(
            'products',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalInventoryUnits'
        ));
    }

    public function movements(Request $request)
    {
        $query = InventoryMovement::with(['product', 'user']);

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $movements = $query->latest()->paginate(20)->withQueryString();

        return view('admin.inventory.movements', compact('movements'));
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $before = $product->stock_qty;
        $qtyInput = (int)$validated['quantity'];

        if ($validated['adjustment_type'] === 'add') {
            $diff = $qtyInput;
            $after = $before + $qtyInput;
            $movementType = 'stock_in';
        } elseif ($validated['adjustment_type'] === 'subtract') {
            $diff = -$qtyInput;
            $after = max(0, $before - $qtyInput);
            $movementType = 'damage';
        } else { // set
            $diff = $qtyInput - $before;
            $after = $qtyInput;
            $movementType = 'adjustment';
        }

        $product->update(['stock_qty' => $after]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'type' => $movementType,
            'qty' => $diff,
            'stock_before' => $before,
            'stock_after' => $after,
            'reason' => $validated['reason'],
            'reference' => 'ADJ-' . strtoupper(substr(md5(microtime()), 0, 6)),
            'user_id' => auth()->id(),
            'notes' => $validated['notes'],
        ]);

        ActivityLog::log('inventory.adjust', "Adjusted stock for {$product->name} ({$before} -> {$after})", $product);

        return back()->with('success', "Stock for {$product->name} successfully adjusted to {$after}.");
    }
}
