@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Product Details</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                    <i class="ti ti-edit me-1"></i> Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back to Catalog
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Left Column: Gallery & Details -->
    <div class="col-lg-8">
        <!-- Main Overview Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-4">
                    <!-- Image -->
                    <div class="col-md-5 text-center">
                        <div class="border rounded p-3 mb-2" style="background: #f8f9fa;">
                            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 280px; object-fit: contain;">
                        </div>
                        @if($product->images->count() > 1)
                        <div class="d-flex gap-2 justify-content-center">
                            @foreach($product->images as $img)
                            <img src="{{ $img->url }}" alt="thumb" class="rounded border p-1" width="50" height="50" style="object-fit: contain;">
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="col-md-7">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($product->status) }}</span>
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark">Featured</span>
                            @endif
                            <span class="text-muted fs-xs">SKU: <code>{{ $product->sku }}</code></span>
                        </div>

                        <h3 class="fw-bold mb-2">{{ $product->name }}</h3>
                        <p class="text-muted fs-sm">{{ $product->short_description ?? 'No short description provided.' }}</p>

                        <!-- Price & Margins -->
                        <div class="d-flex align-items-baseline gap-3 my-3">
                            <h2 class="fw-bold text-primary mb-0">${{ number_format($product->price, 2) }}</h2>
                            @if($product->compare_price)
                                <span class="text-muted text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                            @if($product->profit_margin)
                                <span class="badge bg-success-subtle text-success fs-xs fw-semibold">{{ $product->profit_margin }}% profit margin</span>
                            @endif
                        </div>

                        <!-- Info Grid -->
                        <div class="row g-2 pt-2 border-top fs-sm">
                            <div class="col-6">
                                <span class="text-muted">Category:</span>
                                <strong class="ms-1">{{ $product->category?->name ?? 'Unassigned' }}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Brand:</span>
                                <strong class="ms-1">{{ $product->brand?->name ?? 'Unassigned' }}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Type:</span>
                                <strong class="ms-1">{{ ucfirst($product->type) }}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Stock Status:</span>
                                @if($product->stock_qty <= 0)
                                    <span class="text-danger fw-bold ms-1">Out of Stock</span>
                                @elseif($product->is_low_stock)
                                    <span class="text-warning fw-bold ms-1">Low Stock ({{ $product->stock_qty }})</span>
                                @else
                                    <span class="text-success fw-bold ms-1">In Stock ({{ $product->stock_qty }})</span>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Stock Update Form -->
                        <div class="card bg-light border-0 mt-3 p-2">
                            <form action="{{ route('admin.products.quick-stock', $product) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                <span class="fs-xs fw-bold text-muted text-nowrap">Quick Stock:</span>
                                <input type="number" name="stock_qty" value="{{ $product->stock_qty }}" min="0" class="form-control form-control-sm" style="width: 100px;" required>
                                <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                                    <i class="ti ti-check"></i> Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @if($product->description)
                <div class="mt-4 pt-3 border-top">
                    <h5 class="fw-bold mb-2">Full Description</h5>
                    <div class="text-muted fs-sm lh-base">{!! nl2br(e($product->description)) !!}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Variants (if variable) -->
        @if($product->type === 'variable' && $product->variants->count() > 0)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Variants ({{ $product->variants->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Variant SKU</th>
                            <th>Attributes</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td><code>{{ $variant->sku }}</code></td>
                            <td>
                                @foreach($variant->attributeValues as $val)
                                    <span class="badge bg-light text-dark border">{{ $val->attribute?->name }}: {{ $val->value }}</span>
                                @endforeach
                            </td>
                            <td class="fw-bold">${{ number_format($variant->effective_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $variant->stock_qty > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $variant->stock_qty }} in stock
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $variant->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Customer Reviews -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Customer Reviews ({{ $product->reviews->count() }})</h5>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-link text-decoration-none">Manage Reviews &rarr;</a>
            </div>
            <div class="card-body">
                @forelse($product->reviews as $rev)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <strong>{{ $rev->customer?->full_name ?? 'Verified Buyer' }}</strong>
                            <div class="text-warning fs-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star{{ $i <= $rev->rating ? '-filled' : '' }}"></i>
                                @endfor
                                <span class="text-dark fw-bold ms-1">{{ $rev->rating }}/5</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $rev->status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($rev->status) }}</span>
                            <div class="fs-xs text-muted">{{ $rev->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <h6 class="fw-semibold mb-1">{{ $rev->title }}</h6>
                    <p class="text-muted fs-sm mb-0">{{ $rev->body }}</p>
                </div>
                @empty
                <div class="text-center py-4 text-muted">No reviews submitted yet for this product.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & Stock History -->
    <div class="col-lg-4">
        <!-- Sales Stats Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Sales Performance</h5>
            </div>
            <div class="card-body">
                <div class="row g-2 text-center">
                    <div class="col-6 border-end">
                        <span class="text-muted fs-xs">Total Units Sold</span>
                        <h4 class="fw-bold text-primary mt-1">{{ number_format($totalSalesCount) }}</h4>
                    </div>
                    <div class="col-6">
                        <span class="text-muted fs-xs">Gross Revenue</span>
                        <h4 class="fw-bold text-success mt-1">${{ number_format($totalRevenue, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Movement History -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Stock Movements Log</h5>
                <a href="{{ route('admin.inventory.movements') }}" class="btn btn-sm btn-link text-decoration-none">All &rarr;</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush fs-sm">
                    @forelse($product->inventoryMovements->take(6) as $mov)
                    <li class="list-group-item py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $mov->type) }}</span>
                                <span class="fs-xs text-muted d-block">{{ $mov->reason }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $mov->qty > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $mov->qty > 0 ? '+' . $mov->qty : $mov->qty }}
                                </span>
                                <span class="fs-xs text-muted d-block">{{ $mov->created_at->format('M d, H:i') }}</span>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-3">No inventory movements logged.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
