@extends('layouts.admin')

@section('title', 'RMA Return ' . $orderReturn->return_number)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">RMA Return: {{ $orderReturn->return_number }}</h4>
            <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Returns
            </a>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Return Request Details</h5>
                <span class="badge bg-secondary-subtle text-secondary fs-sm">{{ ucfirst($orderReturn->status) }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="text-muted fs-xs text-uppercase d-block mb-1">Customer Reason for Return:</span>
                    <div class="p-3 bg-light rounded text-dark fs-sm">{{ $orderReturn->reason }}</div>
                </div>

                @if($orderReturn->items->count() > 0)
                <h6 class="fw-bold mb-2">Items to Return:</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Condition</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderReturn->items as $retItem)
                            <tr>
                                <td>{{ $retItem->orderItem?->product_name ?? 'Product' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $retItem->qty }}</span></td>
                                <td><span class="badge bg-info-subtle text-info">{{ ucfirst($retItem->condition) }}</span></td>
                                <td class="text-muted fs-sm">{{ $retItem->reason ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Process RMA</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.returns.status', $orderReturn) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fs-sm">RMA Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="requested" {{ $orderReturn->status === 'requested' ? 'selected' : '' }}>Requested</option>
                            <option value="approved" {{ $orderReturn->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="received" {{ $orderReturn->status === 'received' ? 'selected' : '' }}>Received</option>
                            <option value="refunded" {{ $orderReturn->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="rejected" {{ $orderReturn->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-sm">Admin Audit Notes</label>
                        <textarea name="admin_notes" rows="3" class="form-control form-control-sm" placeholder="Resolution details...">{{ $orderReturn->admin_notes }}</textarea>
                    </div>

                    @if(!$orderReturn->restocked)
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="restock" id="restockCheck" value="1">
                        <label class="form-check-label fs-sm fw-semibold" for="restockCheck">Restock items back into inventory</label>
                    </div>
                    @else
                    <div class="alert alert-success fs-xs py-2 mb-3">
                        <i class="ti ti-check me-1"></i> Inventory already restocked for this return.
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="ti ti-check me-1"></i> Update RMA Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
