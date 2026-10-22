@extends('layouts.admin')

@section('title', 'Shipments & Tracking')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Shipments & Tracking</h4>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Carrier</th>
                    <th>Tracking Number</th>
                    <th>Shipped Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th class="text-end">Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments as $shipment)
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $shipment->order) }}" class="fw-bold text-primary">
                            {{ $shipment->order->order_number }}
                        </a>
                    </td>
                    <td>{{ $shipment->order->customer_name }}</td>
                    <td><strong>{{ $shipment->carrier ?? 'Standard Courier' }}</strong></td>
                    <td><code>{{ $shipment->tracking_number ?? 'Pending' }}</code></td>
                    <td class="fs-xs text-muted">{{ $shipment->shipped_at ? $shipment->shipped_at->format('M d, Y') : '—' }}</td>
                    <td class="fs-xs text-muted">{{ $shipment->delivered_at ? $shipment->delivered_at->format('M d, Y') : '—' }}</td>
                    <td>
                        <span class="badge {{ $shipment->status === 'delivered' ? 'bg-success' : 'bg-info text-dark' }}">
                            {{ ucfirst($shipment->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="modal" data-bs-target="#editShipmentModal{{ $shipment->id }}">
                            <i class="ti ti-edit"></i>
                        </button>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="editShipmentModal{{ $shipment->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.shipping.shipments.update', $shipment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Shipment #{{ $shipment->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Carrier</label>
                                        <input type="text" name="carrier" class="form-control" value="{{ $shipment->carrier }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tracking Number</label>
                                        <input type="text" name="tracking_number" class="form-control" value="{{ $shipment->tracking_number }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Shipment Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="pending" {{ $shipment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $shipment->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $shipment->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="returned" {{ $shipment->status === 'returned' ? 'selected' : '' }}>Returned</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Shipment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No shipments logged.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($shipments->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $shipments->firstItem() }} to {{ $shipments->lastItem() }} of {{ $shipments->total() }} shipments</span>
        {{ $shipments->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
