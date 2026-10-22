@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Product Catalog</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export-products') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-download me-1"></i> Export Catalog
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i> Add Product
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="brand_id" class="form-select form-select-sm">
                    <option value="">All Brands</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">All Stock</option>
                    <option value="in" {{ request('stock_status') === 'in' ? 'selected' : '' }}>In Stock</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-secondary btn-sm w-100" title="Apply Filters">
                    <i class="ti ti-filter"></i>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm" title="Clear Filters">
                    <i class="ti ti-refresh"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Cost</th>
                    <th>Margin</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="rounded" width="48" height="48" style="object-fit: contain; background: #f8f9fa;">
                    </td>
                    <td>
                        <a href="{{ route('admin.products.show', $product) }}" class="fw-bold text-dark text-decoration-none d-block">
                            {{ $product->name }}
                        </a>
                        <span class="fs-xs text-muted">SKU: <code>{{ $product->sku }}</code></span>
                        @if($product->is_featured)
                            <span class="badge bg-warning text-dark fs-xs ms-1">Featured</span>
                        @endif
                    </td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>{{ $product->brand?->name ?? '—' }}</td>
                    <td class="fw-bold">${{ number_format($product->price, 2) }}</td>
                    <td class="text-muted">${{ $product->cost_price ? number_format($product->cost_price, 2) : '—' }}</td>
                    <td>
                        @if($product->profit_margin)
                            <span class="badge bg-success-subtle text-success">{{ $product->profit_margin }}%</span>
                        @else
                            <span class="text-muted fs-xs">—</span>
                        @endif
                    </td>
                    <td>
                        @if($product->stock_qty <= 0)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->is_low_stock)
                            <span class="badge bg-warning text-dark">{{ $product->stock_qty }} left</span>
                        @else
                            <span class="badge bg-success">{{ $product->stock_qty }} in stock</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $product->status === 'active' ? 'bg-success' : ($product->status === 'draft' ? 'bg-secondary' : 'bg-danger') }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="dropdown" type="button">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.products.show', $product) }}">
                                        <i class="ti ti-eye me-2 text-primary"></i> View Details
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                                        <i class="ti ti-edit me-2 text-warning"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('admin.products.duplicate', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="ti ti-copy me-2 text-info"></i> Duplicate
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Move this product to trash?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="ti ti-trash me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">No products found matching your criteria.</td>
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
@endsection
