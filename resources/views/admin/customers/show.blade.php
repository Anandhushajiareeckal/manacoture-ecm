@extends('layouts.admin')

@section('title', 'Customer - ' . $customer->full_name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Customer Details</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                    <i class="ti ti-edit me-1"></i> Edit Profile
                </a>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back to Customers
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Left Column: Profile Card & Addresses -->
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card border-0 shadow-sm mb-3 text-center p-3">
            <div class="card-body">
                <img src="{{ asset($customer->avatar ?? 'assets/images/sellers/1.png') }}" alt="{{ $customer->full_name }}" class="rounded-circle mb-3 border p-1" width="90" height="90" style="object-fit: cover;">
                <h4 class="fw-bold mb-1">{{ $customer->full_name }}</h4>
                <div class="text-muted fs-sm mb-2">{{ $customer->email }}</div>
                <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }} mb-3">
                    {{ $customer->is_active ? 'Active Customer' : 'Deactivated' }}
                </span>

                <div class="row g-2 border-top pt-3 text-start fs-sm">
                    <div class="col-12">
                        <span class="text-muted"><i class="ti ti-phone me-1"></i> Phone:</span>
                        <strong class="ms-1">{{ $customer->phone ?? 'Not provided' }}</strong>
                    </div>
                    <div class="col-12">
                        <span class="text-muted"><i class="ti ti-calendar me-1"></i> Member Since:</span>
                        <strong class="ms-1">{{ $customer->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="col-12">
                        <span class="text-muted"><i class="ti ti-login me-1"></i> Last Activity:</span>
                        <strong class="ms-1">{{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never logged in' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Book Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Saved Addresses</h5>
            </div>
            <div class="card-body">
                @forelse($customer->addresses as $addr)
                <div class="border rounded p-2 mb-2 fs-sm bg-light bg-opacity-25">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-secondary-subtle text-secondary text-uppercase">{{ $addr->type }}</span>
                        @if($addr->is_default)
                            <span class="badge bg-primary fs-xs">Default</span>
                        @endif
                    </div>
                    <div class="fw-bold">{{ $addr->full_name }}</div>
                    <div class="text-muted">{{ $addr->formatted_address }}</div>
                    @if($addr->phone)
                        <div class="text-muted fs-xs mt-1"><i class="ti ti-phone me-1"></i> {{ $addr->phone }}</div>
                    @endif
                </div>
                @empty
                <div class="text-center py-3 text-muted fs-sm">No saved addresses on file.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Purchase History & Reviews -->
    <div class="col-lg-8">
        <!-- Order History -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Purchase History ({{ $customer->orders->count() }})</h5>
                <span class="fs-xs fw-semibold text-success">Total Lifetime Spend: ${{ number_format($customer->total_spent, 2) }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Fulfillment</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $ord)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $ord) }}" class="fw-bold text-primary">
                                    {{ $ord->order_number }}
                                </a>
                            </td>
                            <td class="fs-xs text-muted">{{ $ord->created_at->format('M d, Y') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $ord->items->count() }} items</span></td>
                            <td class="fw-bold">${{ number_format($ord->grand_total, 2) }}</td>
                            <td><span class="{{ $ord->payment_badge_class }}">{{ ucfirst($ord->payment_status) }}</span></td>
                            <td><span class="{{ $ord->fulfillment_badge_class }}">{{ ucfirst($ord->fulfillment_status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-sm btn-light btn-icon" title="View Order">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No orders placed by this customer yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Product Reviews written by customer -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Submitted Reviews ({{ $customer->reviews->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($customer->reviews as $rev)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <strong>{{ $rev->product?->name ?? 'Product' }}</strong>
                            <div class="text-warning fs-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ti ti-star{{ $i <= $rev->rating ? '-filled' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span class="badge {{ $rev->status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($rev->status) }}</span>
                    </div>
                    <h6 class="fw-semibold mb-1 fs-sm">{{ $rev->title }}</h6>
                    <p class="text-muted fs-sm mb-0">{{ $rev->body }}</p>
                </div>
                @empty
                <div class="text-center py-3 text-muted fs-sm">Customer has not submitted any product reviews.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
