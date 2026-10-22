@extends('layouts.admin')

@section('title', 'Create Manual Order')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Create New Order</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.orders.store') }}" method="POST">
    @csrf

    <div class="row g-3">
        <div class="col-lg-8">
            <!-- Customer Selection -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <label class="form-label">Select Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">-- Choose Existing Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->full_name }} ({{ $c->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Items Selection -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Order Items</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-item-btn">
                        <i class="ti ti-plus me-1"></i> Add Another Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <div class="row g-2 mb-3 item-row align-items-end">
                            <div class="col-md-8">
                                <label class="form-label fs-xs text-muted">Select Product</label>
                                <select name="items[0][product_id]" class="form-select product-select" required>
                                    <option value="">-- Choose Product --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->price }}">
                                            {{ $p->name }} (${{ number_format($p->price, 2) }} - Stock: {{ $p->stock_qty }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-xs text-muted">Quantity</label>
                                <input type="number" name="items[0][qty]" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-light text-danger w-100 remove-item-btn disabled" disabled>
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Customer & Delivery Notes</h5>
                </div>
                <div class="card-body">
                    <textarea name="notes" rows="3" class="form-control" placeholder="Special delivery instructions, gate code, notes...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Configuration -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Payment & Shipping</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Shipping Method</label>
                        <select name="shipping_method_id" class="form-select">
                            <option value="">Standard Carrier</option>
                            @foreach($shippingMethods as $sm)
                                <option value="{{ $sm->id }}">{{ $sm->name }} (${{ number_format($sm->base_rate, 2) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="PayPal Express">PayPal Express</option>
                            <option value="Bank Transfer">Direct Bank Transfer</option>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Initial Payment Status <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-select" required>
                            <option value="pending">Pending Payment</option>
                            <option value="paid" selected>Mark as Paid</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Initial Fulfillment Status <span class="text-danger">*</span></label>
                        <select name="fulfillment_status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="processing" selected>Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="card border-0 shadow-sm">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                        <i class="ti ti-check me-1"></i> Place & Create Order
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var container = document.getElementById('items-container');
        var addBtn = document.getElementById('add-item-btn');
        var itemIndex = 1;

        addBtn.addEventListener('click', function () {
            var firstRow = container.querySelector('.item-row');
            var newRow = firstRow.cloneNode(true);

            newRow.querySelector('select').name = 'items[' + itemIndex + '][product_id]';
            newRow.querySelector('select').value = '';
            newRow.querySelector('input').name = 'items[' + itemIndex + '][qty]';
            newRow.querySelector('input').value = 1;

            var removeBtn = newRow.querySelector('.remove-item-btn');
            removeBtn.classList.remove('disabled');
            removeBtn.removeAttribute('disabled');

            removeBtn.addEventListener('click', function () {
                newRow.remove();
            });

            container.appendChild(newRow);
            itemIndex++;
        });
    });
</script>
@endpush
