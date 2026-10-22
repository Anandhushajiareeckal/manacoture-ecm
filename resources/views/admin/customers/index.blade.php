@extends('layouts.admin')

@section('title', 'Customers Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Customer Accounts</h4>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-user-plus me-1"></i> Add Customer
            </a>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by customer name, email, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Account Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Accounts</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Accounts</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-secondary btn-sm w-100" title="Search">
                    <i class="ti ti-search me-1"></i> Search
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm" title="Clear">
                    <i class="ti ti-refresh"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">Avatar</th>
                    <th>Customer Name</th>
                    <th>Contact Email</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Total Spend</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>
                        <img src="{{ asset($customer->avatar ?? 'assets/images/sellers/1.png') }}" alt="{{ $customer->full_name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $customer->full_name }}
                        </a>
                        <div class="fs-xs text-muted">Member since {{ $customer->created_at->format('M Y') }}</div>
                    </td>
                    <td><a href="mailto:{{ $customer->email }}" class="text-muted">{{ $customer->email }}</a></td>
                    <td>{{ $customer->phone ?? '—' }}</td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary">{{ $customer->orders_count }} orders</span>
                    </td>
                    <td class="fw-bold text-success">${{ number_format($customer->total_spent, 2) }}</td>
                    <td>
                        <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="Toggle active status">
                                <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $customer->is_active ? 'Active' : 'Deactivated' }}
                                </span>
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-light btn-icon" title="View Profile">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-light btn-icon" title="Edit Profile">
                                <i class="ti ti-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No customers registered yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers</span>
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
