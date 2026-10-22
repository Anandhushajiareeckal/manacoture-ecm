<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\InventoryMovement;
use App\Models\ActivityLog;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($stockStatus = $request->input('stock_status')) {
            if ($stockStatus === 'low') {
                $query->whereColumn('stock_qty', '<=', 'low_stock_threshold');
            } elseif ($stockStatus === 'out') {
                $query->where('stock_qty', '<=', 0);
            } elseif ($stockStatus === 'in') {
                $query->where('stock_qty', '>', 0);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'type' => 'required|in:simple,variable',
            'price' => 'required|numeric|min:0|max:999999999999.99',
            'cost_price' => 'nullable|numeric|min:0|max:999999999999.99',
            'compare_price' => 'nullable|numeric|min:0|max:999999999999.99',
            'stock_qty' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive,archived',
            'is_featured' => 'boolean',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'fabric' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'occasion' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'hover_image' => 'nullable',
            'is_new' => 'boolean',
            'main_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        if ($request->hasFile('hover_image')) {
            $hPath = $request->file('hover_image')->store('products', 'public');
            $validated['hover_image'] = 'storage/' . $hPath;
        }

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }
        if ($request->hasFile('hover_image')) {
            $hPath = $request->file('hover_image')->store('products', 'public');
            $validated['hover_image'] = 'storage/' . $hPath;
        }

        $product = Product::create($validated);

        // Record initial inventory movement
        if ($product->stock_qty > 0) {
            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'stock_in',
                'qty' => $product->stock_qty,
                'stock_before' => 0,
                'stock_after' => $product->stock_qty,
                'reason' => 'Initial Product Stock',
                'reference' => 'PROD-NEW-' . $product->id,
                'user_id' => auth()->id(),
            ]);
        }

        ActivityLog::log('product.create', "Created product {$product->name} (SKU: {$product->sku})", $product);

        return redirect()->route('admin.products.show', $product)->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'variants', 'reviews.customer', 'inventoryMovements.user']);
        $totalSalesCount = $product->orderItems()->sum('qty');
        $totalRevenue = $product->orderItems()->sum('line_total');

        return view('admin.products.show', compact('product', 'totalSalesCount', 'totalRevenue'));
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => "required|string|max:100|unique:products,sku,{$product->id}",
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'type' => 'required|in:simple,variable',
            'price' => 'required|numeric|min:0|max:999999999999.99',
            'cost_price' => 'nullable|numeric|min:0|max:999999999999.99',
            'compare_price' => 'nullable|numeric|min:0|max:999999999999.99',
            'stock_qty' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive,archived',
            'is_featured' => 'boolean',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'fabric' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'occasion' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'hover_image' => 'nullable',
            'is_new' => 'boolean',
            'main_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        if ($request->hasFile('hover_image')) {
            $hPath = $request->file('hover_image')->store('products', 'public');
            $validated['hover_image'] = 'storage/' . $hPath;
        }

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }
        if ($request->hasFile('hover_image')) {
            $hPath = $request->file('hover_image')->store('products', 'public');
            $validated['hover_image'] = 'storage/' . $hPath;
        }

        // Track stock changes
        $oldStock = $product->stock_qty;
        $newStock = (int)$validated['stock_qty'];
        if ($oldStock !== $newStock) {
            $diff = $newStock - $oldStock;
            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => $diff > 0 ? 'stock_in' : 'adjustment',
                'qty' => $diff,
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'reason' => 'Manual Stock Adjustment via Product Edit',
                'user_id' => auth()->id(),
            ]);
        }

        $product->update($validated);
        ActivityLog::log('product.update', "Updated product {$product->name}", $product);

        return redirect()->route('admin.products.show', $product)->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        ActivityLog::log('product.delete', "Deleted product {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'Product moved to trash.');
    }

    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->slug = Str::slug($newProduct->name) . '-' . Str::random(5);
        $newProduct->sku = $product->sku . '-COPY-' . strtoupper(Str::random(4));
        $newProduct->status = 'draft';
        $newProduct->save();

        ActivityLog::log('product.duplicate', "Cloned product from {$product->sku} to {$newProduct->sku}", $newProduct);

        return redirect()->route('admin.products.edit', $newProduct)->with('success', 'Product duplicated successfully as draft.');
    }

    public function quickStock(Request $request, Product $product)
    {
        $request->validate(['stock_qty' => 'required|integer|min:0']);
        $old = $product->stock_qty;
        $new = (int)$request->input('stock_qty');
        $product->update(['stock_qty' => $new]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'qty' => $new - $old,
            'stock_before' => $old,
            'stock_after' => $new,
            'reason' => 'Quick Stock Adjustment',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', "Stock for {$product->name} updated to {$new}.");
    }
}
