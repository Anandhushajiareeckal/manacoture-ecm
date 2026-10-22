@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Orders Management</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export-orders') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-download me-1"></i> Export Orders
                </a>
                <a href="{{ route('admin.orders.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i> Create Order
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Status Filter Tabs -->
<ul class="nav nav-pills mb-3 gap-1">
    <li class="nav-item">
        <a class="nav-link {{ request('fulfillment_status') === null ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
            All Orders <span class="badge bg-light text-dark ms-1">{{ $allCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('fulfillment_status') === 'pending' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['fulfillment_status' => 'pending']) }}">
            Pending <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $pendingCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('fulfillment_status') === 'processing' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['fulfillment_status' => 'processing']) }}">
            Processing <span class="badge bg-warning-subtle text-warning ms-1">{{ $processingCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('fulfillment_status') === 'shipped' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['fulfillment_status' => 'shipped']) }}">
            Shipped <span class="badge bg-info-subtle text-info ms-1">{{ $shippedCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('fulfillment_status') === 'delivered' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['fulfillment_status' => 'delivered']) }}">
            Delivered <span class="badge bg-success-subtle text-success ms-1">{{ $deliveredCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('fulfillment_status') === 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['fulfillment_status' => 'cancelled']) }}">
            Cancelled <span class="badge bg-danger-subtle text-danger ms-1">{{ $cancelledCount }}</span>
        </a>
    </li>
</ul>

<!-- Search & Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-center">
            @if(request('fulfillment_status'))
                <input type="hidden" name="fulfillment_status" value="{{ request('fulfillment_status') }}">
            @endif
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search order #, customer name, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="payment_status" class="form-select form-select-sm">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}" title="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}" title="To Date">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-secondary btn-sm w-100" title="Filter">
                    <i class="ti ti-filter"></i>
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm" title="Clear">
                    <i class="ti ti-refresh"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Fulfillment</th>
                    <th>Method</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-primary">
                            {{ $order->order_number }}
                        </a>
                    </td>
                    <td class="fs-xs text-muted">
                        {{ $order->created_at->format('M d, Y') }}
                        <div class="fs-xs text-muted">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td>
                        @if($order->customer)
                            <a href="{{ route('admin.customers.show', $order->customer) }}" class="fw-semibold text-dark text-decoration-none d-block">
                                {{ $order->customer->full_name }}
                            </a>
                            <span class="fs-xs text-muted">{{ $order->customer->email }}</span>
                        @else
                            <span class="fw-semibold text-dark">{{ $order->customer_name }}</span>
                            <span class="fs-xs text-muted d-block">Guest Checkout</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $order->items->count() }} items</span>
                    </td>
                    <td class="fw-bold fs-md">${{ number_format($order->grand_total, 2) }}</td>
                    <td>
                        <span class="{{ $order->payment_badge_class }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $order->fulfillment_badge_class }}">
                            {{ ucfirst($order->fulfillment_status) }}
                        </span>
                    </td>
                    <td class="fs-xs text-muted">{{ $order->payment_method ?? 'Credit Card' }}</td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light btn-icon" title="View Details">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-sm btn-light btn-icon" title="Print Invoice" target="_blank">
                                <i class="ti ti-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">No orders found matching your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders</span>
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
