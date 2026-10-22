@extends('layouts.admin')

@section('title', 'Shipping Methods')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Shipping Methods & Rates</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createShippingModal">
                <i class="ti ti-plus me-1"></i> Add Shipping Method
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Method Name</th>
                    <th>Delivery Description</th>
                    <th>Standard Rate</th>
                    <th>Free Shipping Threshold</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($methods as $m)
                <tr>
                    <td>
                        <strong class="text-dark">{{ $m->name }}</strong>
                    </td>
                    <td class="text-muted fs-sm">{{ $m->description ?? '—' }}</td>
                    <td class="fw-bold">${{ number_format($m->base_rate, 2) }}</td>
                    <td>
                        @if($m->free_threshold)
                            <span class="badge bg-success-subtle text-success">Orders over ${{ number_format($m->free_threshold, 2) }}</span>
                        @else
                            <span class="text-muted fs-xs">No free threshold</span>
                        @endif
                    </td>
                    <td>{{ $m->sort_order }}</td>
                    <td>
                        <span class="badge {{ $m->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $m->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="modal" data-bs-target="#editShippingModal{{ $m->id }}" title="Edit">
                            <i class="ti ti-edit"></i>
                        </button>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editShippingModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.shipping.methods.update', $m) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Shipping Method</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Method Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ $m->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description / Estimated Delivery</label>
                                        <input type="text" name="description" class="form-control" value="{{ $m->description }}">
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Base Rate ($) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="base_rate" class="form-control" value="{{ $m->base_rate }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Free Threshold ($)</label>
                                            <input type="number" step="0.01" name="free_threshold" class="form-control" value="{{ $m->free_threshold }}">
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="mActive{{ $m->id }}" value="1" {{ $m->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="mActive{{ $m->id }}">Active</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No shipping methods configured.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createShippingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.shipping.methods.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Shipping Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Method Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Express Air 2-Day" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description / Estimated Delivery</label>
                        <input type="text" name="description" class="form-control" placeholder="Delivery in 2 business days">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Base Rate ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="base_rate" class="form-control" placeholder="9.99" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Free Threshold ($)</label>
                            <input type="number" step="0.01" name="free_threshold" class="form-control" placeholder="100.00">
                        </div>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="mCreateActive" value="1" checked>
                        <label class="form-check-label" for="mCreateActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Method</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
