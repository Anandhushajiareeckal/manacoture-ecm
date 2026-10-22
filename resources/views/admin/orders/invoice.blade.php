<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-light py-4">
    <div class="container">
        <!-- Action Bar -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Order Details
            </a>
            <button onclick="window.print();" class="btn btn-primary btn-sm">
                <i class="ti ti-printer me-1"></i> Print Invoice
            </button>
        </div>

        <!-- Invoice Paper -->
        <div class="card border-0 shadow-sm p-4 p-md-5 bg-white">
            <!-- Header -->
            <div class="row align-items-center pb-4 border-bottom mb-4">
                <div class="col-sm-6">
                    <img src="{{ asset('assets/images/logo-black.png') }}" alt="logo" height="32" class="mb-2">
                    <h5 class="fw-bold mb-1">{{ $storeName }}</h5>
                    <p class="text-muted fs-sm mb-0">
                        {{ $storeAddress }}<br>
                        Email: {{ $storeEmail }} | Phone: {{ $storePhone }}
                    </p>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <h3 class="fw-bold text-uppercase text-primary mb-1">INVOICE</h3>
                    <h5 class="fw-semibold mb-1">{{ $order->order_number }}</h5>
                    <div class="fs-sm text-muted">Date: {{ $order->created_at->format('M d, Y') }}</div>
                    <span class="badge {{ $order->payment_badge_class }} mt-1">Payment: {{ ucfirst($order->payment_status) }}</span>
                </div>
            </div>

            <!-- Bill To & Ship To -->
            <div class="row mb-4 fs-sm">
                <div class="col-sm-6">
                    <h6 class="fw-bold text-uppercase text-muted fs-xs mb-2">Billed To:</h6>
                    <h6 class="fw-bold mb-1">{{ $order->customer_name }}</h6>
                    <p class="text-muted mb-0">
                        {{ $order->billing_address['address_line1'] ?? '' }}<br>
                        {{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['postal_code'] ?? '' }}<br>
                        {{ $order->billing_address['country'] ?? 'US' }}<br>
                        Email: {{ $order->customer?->email ?? 'N/A' }}
                    </p>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <h6 class="fw-bold text-uppercase text-muted fs-xs mb-2">Shipped To:</h6>
                    <h6 class="fw-bold mb-1">{{ $order->shipping_address['full_name'] ?? $order->customer_name }}</h6>
                    <p class="text-muted mb-0">
                        {{ $order->shipping_address['address_line1'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                        {{ $order->shipping_address['country'] ?? 'US' }}
                    </p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Item & Description</th>
                            <th>SKU</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                            </td>
                            <td><code>{{ $item->product_sku }}</code></td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end fw-bold">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="row justify-content-end mb-4">
                <div class="col-sm-5 col-md-4">
                    <div class="d-flex justify-content-between mb-2 fs-sm">
                        <span class="text-muted">Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_total > 0)
                    <div class="d-flex justify-content-between mb-2 fs-sm text-success">
                        <span>Discount:</span>
                        <span>-${{ number_format($order->discount_total, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2 fs-sm">
                        <span class="text-muted">Shipping:</span>
                        <span>${{ number_format($order->shipping_total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 fs-sm">
                        <span class="text-muted">Tax:</span>
                        <span>${{ number_format($order->tax_total, 2) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fs-md">
                        <strong class="text-dark">Total Due:</strong>
                        <strong class="text-primary fs-lg">${{ number_format($order->grand_total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Notes & Footer -->
            <div class="border-top pt-3 text-center text-muted fs-xs">
                <p class="mb-1">Thank you for your business with {{ $storeName }}.</p>
                <p class="mb-0">If you have questions about this invoice, please reach out to {{ $storeEmail }}.</p>
            </div>
        </div>
    </div>
</body>
</html>
