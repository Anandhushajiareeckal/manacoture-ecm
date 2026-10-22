@extends('layouts.admin')

@section('title', 'Stock Movement Ledger')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Inventory Movement Ledger</h4>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Inventory
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.inventory.movements') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search reference, reason, product..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Movement Types</option>
                    <option value="stock_in" {{ request('type') === 'stock_in' ? 'selected' : '' }}>Stock In (Intake)</option>
                    <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>Sale (Order Placed)</option>
                    <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>Customer Return</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Audit Adjustment</option>
                    <option value="damage" {{ request('type') === 'damage' ? 'selected' : '' }}>Damaged / Loss</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-secondary btn-sm w-100"><i class="ti ti-filter"></i> Filter</button>
                <a href="{{ route('admin.inventory.movements') }}" class="btn btn-light btn-sm"><i class="ti ti-refresh"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Movement Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date & Time</th>
                    <th>Product</th>
                    <th>Movement Type</th>
                    <th>Change</th>
                    <th>Before &rarr; After</th>
                    <th>Reference</th>
                    <th>Reason / Notes</th>
                    <th>Logged By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $mov)
                <tr>
                    <td class="fs-xs text-muted">
                        {{ $mov->created_at->format('M d, Y') }}
                        <div class="fs-xs">{{ $mov->created_at->format('H:i') }}</div>
                    </td>
                    <td>
                        @if($mov->product)
                            <a href="{{ route('admin.products.show', $mov->product) }}" class="fw-semibold text-dark text-decoration-none">
                                {{ $mov->product->name }}
                            </a>
                            <div class="fs-xs text-muted">SKU: <code>{{ $mov->product->sku }}</code></div>
                        @else
                            <span class="text-muted">Deleted Product</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border text-capitalize">
                            {{ str_replace('_', ' ', $mov->type) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $mov->qty > 0 ? 'bg-success' : 'bg-danger' }} fs-xs">
                            {{ $mov->qty > 0 ? '+' . $mov->qty : $mov->qty }}
                        </span>
                    </td>
                    <td class="fs-sm">
                        <span class="text-muted">{{ $mov->stock_before }}</span>
                        <i class="ti ti-arrow-right mx-1 text-muted fs-xs"></i>
                        <strong class="text-dark">{{ $mov->stock_after }}</strong>
                    </td>
                    <td><code>{{ $mov->reference ?? '—' }}</code></td>
                    <td class="fs-sm">
                        <span class="fw-semibold text-dark">{{ $mov->reason ?? '—' }}</span>
                        @if($mov->notes)
                            <div class="fs-xs text-muted">{{ $mov->notes }}</div>
                        @endif
                    </td>
                    <td class="fs-xs text-muted">{{ $mov->user?->name ?? 'System' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No stock movements recorded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($movements->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $movements->firstItem() }} to {{ $movements->lastItem() }} of {{ $movements->total() }} entries</span>
        {{ $movements->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
