@extends('layouts.admin')

@section('title', 'Returns & Exchanges')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Customer Returns & RMA</h4>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>RMA #</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Reason</th>
                    <th>Restocked</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $ret)
                <tr>
                    <td>
                        <a href="{{ route('admin.returns.show', $ret) }}" class="fw-bold text-primary">
                            {{ $ret->return_number }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $ret->order) }}" class="text-dark">
                            {{ $ret->order->order_number }}
                        </a>
                    </td>
                    <td>{{ $ret->customer?->full_name ?? $ret->order->customer_name }}</td>
                    <td class="text-muted fs-sm text-truncate" style="max-width: 250px;">{{ $ret->reason }}</td>
                    <td>
                        <span class="badge {{ $ret->restocked ? 'bg-success-subtle text-success' : 'bg-light text-muted' }}">
                            {{ $ret->restocked ? 'Restocked' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($ret->status) }}</span>
                    </td>
                    <td class="fs-xs text-muted">{{ $ret->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.returns.show', $ret) }}" class="btn btn-sm btn-light btn-icon" title="View RMA">
                            <i class="ti ti-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No returns or RMA requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($returns->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $returns->firstItem() }} to {{ $returns->lastItem() }} of {{ $returns->total() }} returns</span>
        {{ $returns->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
