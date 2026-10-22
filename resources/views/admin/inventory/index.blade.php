@extends('layouts.admin')

@section('title', 'Inventory & Stock Levels')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Inventory & Stock Levels</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.inventory.movements') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-history me-1"></i> Stock Movement Ledger
                </a>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stockAdjustmentModal">
                    <i class="ti ti-adjustments me-1"></i> Manual Stock Adjustment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Metric Cards -->
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Total Stock Units</span>
                <h3 class="fw-bold mb-0 mt-1">{{ number_format($totalInventoryUnits) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Catalog SKUs</span>
                <h3 class="fw-bold mb-0 mt-1">{{ number_format($totalProducts) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-warning fs-xs fw-semibold"><i class="ti ti-alert-triangle"></i> Low Stock Warnings</span>
                <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($lowStockCount) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-danger fs-xs fw-semibold"><i class="ti ti-circle-x"></i> Out of Stock</span>
                <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($outOfStockCount) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by SKU, product title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="filter" class="form-select form-select-sm">
                    <option value="">All Stock Levels</option>
                    <option value="low" {{ request('filter') === 'low' ? 'selected' : '' }}>Low Stock Alerts Only</option>
                    <option value="out" {{ request('filter') === 'out' ? 'selected' : '' }}>Out of Stock Only</option>
                    <option value="in" {{ request('filter') === 'in' ? 'selected' : '' }}>Adequately Stocked</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-secondary btn-sm w-100"><i class="ti ti-filter"></i> Filter</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-light btn-sm"><i class="ti ti-refresh"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Stock Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">Image</th>
                    <th>Product & SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Current Stock</th>
                    <th>Alert Level</th>
                    <th>Stock Status</th>
                    <th class="text-end">Quick Adjustment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                <tr>
                    <td>
                        <img src="{{ $prod->main_image_url }}" alt="{{ $prod->name }}" class="rounded" width="45" height="45" style="object-fit: contain; background: #f8f9fa;">
                    </td>
                    <td>
                        <a href="{{ route('admin.products.show', $prod) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $prod->name }}
                        </a>
                        <div class="fs-xs text-muted">SKU: <code>{{ $prod->sku }}</code></div>
                    </td>
                    <td>{{ $prod->category?->name ?? '—' }}</td>
                    <td class="fw-bold">${{ number_format($prod->price, 2) }}</td>
                    <td>
                        <span class="fw-bold fs-md">{{ $prod->stock_qty }}</span>
                    </td>
                    <td class="text-muted fs-xs">&le; {{ $prod->low_stock_threshold }} units</td>
                    <td>
                        @if($prod->stock_qty <= 0)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($prod->is_low_stock)
                            <span class="badge bg-warning text-dark"><i class="ti ti-alert-triangle"></i> Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.products.quick-stock', $prod) }}" method="POST" class="d-inline-flex align-items-center gap-1 justify-content-end">
                            @csrf
                            <input type="number" name="stock_qty" value="{{ $prod->stock_qty }}" min="0" class="form-control form-control-sm text-center" style="width: 75px;" required>
                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Update Stock">
                                <i class="ti ti-check"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No products found matching stock criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products</span>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.inventory.adjust') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manual Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Choose Product --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Current: {{ $p->stock_qty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Action Type <span class="text-danger">*</span></label>
                            <select name="adjustment_type" class="form-select" required>
                                <option value="add">Add Stock (Receive/Intake)</option>
                                <option value="subtract">Deduct Stock (Damage/Shrinkage)</option>
                                <option value="set">Set Exact Stock Count</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" min="0" required placeholder="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g. Supplier Shipment, Physical Inventory Count" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Optional Notes</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Additional audit remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Adjustment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
