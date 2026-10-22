@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <h4 class="mb-0">Order: {{ $order->order_number }}</h4>
                <span class="{{ $order->fulfillment_badge_class }}">{{ ucfirst($order->fulfillment_status) }}</span>
                <span class="{{ $order->payment_badge_class }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-printer me-1"></i> Print Invoice
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Left Column: Items & Totals & History -->
    <div class="col-lg-8">
        <!-- Order Items Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Order Items ({{ $order->items->count() }})</h5>
                <span class="fs-xs text-muted">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">Item</th>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->product ? $item->product->main_image_url : asset('assets/images/products/1.png') }}" alt="{{ $item->product_name }}" class="rounded" width="45" height="45" style="object-fit: contain; background: #f8f9fa;">
                            </td>
                            <td>
                                @if($item->product)
                                    <a href="{{ route('admin.products.show', $item->product) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <span class="fw-semibold text-dark">{{ $item->product_name }}</span>
                                @endif
                                <div class="fs-xs text-muted">SKU: <code>{{ $item->product_sku }}</code></div>
                            </td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->qty }}</span></td>
                            <td class="text-end fw-bold">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary -->
            <div class="card-body bg-light bg-opacity-25 border-top">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between mb-2 fs-sm">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-semibold">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount_total > 0)
                        <div class="d-flex justify-content-between mb-2 fs-sm text-success">
                            <span>Discount @if($order->coupon) (<code>{{ $order->coupon->code }}</code>) @endif:</span>
                            <span>-${{ number_format($order->discount_total, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2 fs-sm">
                            <span class="text-muted">Shipping Total:</span>
                            <span>${{ number_format($order->shipping_total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 fs-sm">
                            <span class="text-muted">Estimated Tax (8.25%):</span>
                            <span>${{ number_format($order->tax_total, 2) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fs-md">
                            <strong class="text-dark">Grand Total:</strong>
                            <strong class="text-primary fs-lg">${{ number_format($order->grand_total, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipments & Tracking Card -->
        @if($order->shipments->count() > 0)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Shipment Information</h5>
            </div>
            <div class="card-body">
                @foreach($order->shipments as $shipment)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>Carrier: {{ $shipment->carrier ?? 'Standard Courier' }}</strong>
                        <div class="fs-xs text-muted">Tracking #: <code>{{ $shipment->tracking_number ?? 'Pending Tracking #' }}</code></div>
                    </div>
                    <div>
                        <span class="badge bg-info-subtle text-info">{{ ucfirst($shipment->status) }}</span>
                        @if($shipment->shipped_at)
                            <div class="fs-xs text-muted">Shipped: {{ $shipment->shipped_at->format('M d, Y') }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Order Activity Timeline -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Order Timeline & Audit Trail</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($order->statusHistory as $hist)
                    <li class="list-group-item d-flex align-items-start gap-3 py-2 px-0">
                        <span class="avatar-xs rounded-circle bg-light d-flex align-items-center justify-content-center text-primary mt-1">
                            <i class="ti ti-check"></i>
                        </span>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark">{{ $hist->status }}</strong>
                                <span class="fs-xs text-muted">{{ $hist->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-muted fs-xs mb-0">{{ $hist->note ?? 'No notes entered.' }}</p>
                            @if($hist->user)
                                <span class="fs-xs text-muted">by <em>{{ $hist->user->name }}</em></span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-3">No activity recorded yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Change & Customer & Addresses -->
    <div class="col-lg-4">
        <!-- Status Transition Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Update Order Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label fs-sm">Fulfillment Status</label>
                        <select name="fulfillment_status" class="form-select form-select-sm">
                            <option value="pending" {{ $order->fulfillment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->fulfillment_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->fulfillment_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->fulfillment_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->fulfillment_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-sm">Payment Status</label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-sm">Internal Note</label>
                        <textarea name="note" rows="2" class="form-control form-control-sm" placeholder="Add optional status update note..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="ti ti-refresh me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Profile Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Customer Information</h5>
                @if($order->customer)
                    <a href="{{ route('admin.customers.show', $order->customer) }}" class="btn btn-sm btn-link p-0 text-decoration-none">Profile &rarr;</a>
                @endif
            </div>
            <div class="card-body fs-sm">
                @if($order->customer)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset($order->customer->avatar ?? 'assets/images/sellers/1.png') }}" alt="{{ $order->customer->full_name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        <div>
                            <strong>{{ $order->customer->full_name }}</strong>
                            <div class="text-muted fs-xs">{{ $order->customer->email }}</div>
                        </div>
                    </div>
                    <div class="mb-1"><i class="ti ti-phone me-1 text-muted"></i> {{ $order->customer->phone ?? 'Not provided' }}</div>
                    <div class="mb-1"><i class="ti ti-shopping-bag me-1 text-muted"></i> Total Orders: <strong>{{ $order->customer->orders_count }}</strong></div>
                    <div><i class="ti ti-cash me-1 text-muted"></i> Lifetime Spend: <strong>${{ number_format($order->customer->total_spent, 2) }}</strong></div>
                @else
                    <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                    <span class="badge bg-light text-muted border">Guest Customer</span>
                @endif
            </div>
        </div>

        <!-- Shipping & Billing Addresses Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Delivery & Billing Addresses</h5>
            </div>
            <div class="card-body fs-sm">
                <h6 class="fw-bold fs-xs text-uppercase text-muted mb-1"><i class="ti ti-truck me-1"></i> Shipping Address</h6>
                <p class="text-dark mb-3">
                    {{ $order->shipping_address['full_name'] ?? $order->customer_name }}<br>
                    {{ $order->shipping_address['address_line1'] ?? 'No address line' }}<br>
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                    {{ $order->shipping_address['country'] ?? 'US' }}
                </p>

                <h6 class="fw-bold fs-xs text-uppercase text-muted mb-1"><i class="ti ti-receipt me-1"></i> Billing Address</h6>
                <p class="text-dark mb-0">
                    {{ $order->billing_address['full_name'] ?? $order->customer_name }}<br>
                    {{ $order->billing_address['address_line1'] ?? 'No address line' }}<br>
                    {{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['postal_code'] ?? '' }}<br>
                    {{ $order->billing_address['country'] ?? 'US' }}
                </p>
            </div>
        </div>

        <!-- Payment Details Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Payment Details</h5>
            </div>
            <div class="card-body fs-sm">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Method:</span>
                    <strong>{{ $order->payment_method ?? 'Credit Card' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Status:</span>
                    <span class="{{ $order->payment_badge_class }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Paid:</span>
                    <strong class="text-success">${{ number_format($order->grand_total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
