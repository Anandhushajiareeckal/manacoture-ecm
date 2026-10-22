@extends('layouts.admin')

@section('title', 'Coupons & Discounts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Coupons & Promo Codes</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCouponModal">
                <i class="ti ti-plus me-1"></i> Create Coupon
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Coupon Code</th>
                    <th>Discount Type</th>
                    <th>Value</th>
                    <th>Min. Order</th>
                    <th>Max Discount</th>
                    <th>Usage / Limit</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <strong class="text-primary fs-md"><code>{{ $coupon->code }}</code></strong>
                        @if($coupon->description)
                            <div class="fs-xs text-muted">{{ $coupon->description }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $coupon->type === 'percent' ? 'Percentage Off' : 'Fixed Amount' }}
                        </span>
                    </td>
                    <td class="fw-bold">
                        {{ $coupon->type === 'percent' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
                    </td>
                    <td>${{ $coupon->min_order ? number_format($coupon->min_order, 2) : '0.00' }}</td>
                    <td>{{ $coupon->max_discount ? '$' . number_format($coupon->max_discount, 2) : 'No limit' }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '&infin;' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="Toggle Active">
                                <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger btn-icon" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No coupons created yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Coupon Modal -->
<div class="modal fade" id="createCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Promo Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. FLASH20" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="percent">Percentage (%)</option>
                                <option value="fixed">Fixed Dollar ($)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="value" class="form-control" placeholder="10" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Min. Order Value ($)</label>
                            <input type="number" step="0.01" name="min_order" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Discount Cap ($)</label>
                            <input type="number" step="0.01" name="max_discount" class="form-control" placeholder="50.00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Maximum Redemptions</label>
                        <input type="number" name="max_uses" class="form-control" placeholder="Unlimited">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Campaign Purpose</label>
                        <textarea name="description" rows="2" class="form-control" placeholder="Promotion summary..."></textarea>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="couponActive" value="1" checked>
                        <label class="form-check-label" for="couponActive">Activate Immediately</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Coupon</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
