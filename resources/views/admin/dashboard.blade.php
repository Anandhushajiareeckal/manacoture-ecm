@extends('layouts.admin')

@section('title', 'E-Commerce Dashboard')

@section('content')
<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">E-Commerce Overview</h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('admin.reports.export-orders') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-download me-1"></i> Export Orders
                </a>
                <a href="{{ route('admin.orders.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i> New Order
                </a>
            </div>
        </div>
    </div>
</div>

<!-- KPI Metrics Row -->
<div class="row g-3 my-2">
    <!-- Total Revenue -->
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-semibold">Total Revenue</span>
                    <span class="avatar-sm rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center">
                        <i class="ti ti-currency-dollar fs-lg"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-1">${{ number_format($totalRevenue, 2) }}</h3>
                <div class="fs-xs text-muted">
                    <span class="text-success fw-bold me-1"><i class="ti ti-trending-up"></i> +{{ $revenueGrowth }}%</span>
                    vs last 30 days
                </div>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-semibold">Total Orders</span>
                    <span class="avatar-sm rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                        <i class="ti ti-shopping-cart fs-lg"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalOrders) }}</h3>
                <div class="fs-xs text-muted">
                    <span class="text-success fw-bold me-1"><i class="ti ti-trending-up"></i> +{{ $ordersGrowth }}%</span>
                    from paid checkouts
                </div>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-semibold">Registered Customers</span>
                    <span class="avatar-sm rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center">
                        <i class="ti ti-users fs-lg"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-1">{{ number_format($totalCustomers) }}</h3>
                <div class="fs-xs text-muted">
                    <span class="text-info fw-bold me-1"><i class="ti ti-user-check"></i> Active</span>
                    across store accounts
                </div>
            </div>
        </div>
    </div>

    <!-- Average Order Value (AOV) -->
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-semibold">Average Order Value</span>
                    <span class="avatar-sm rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
                        <i class="ti ti-receipt-tax fs-lg"></i>
                    </span>
                </div>
                <h3 class="fw-bold mb-1">${{ number_format($avgOrderValue, 2) }}</h3>
                <div class="fs-xs text-muted">
                    <span class="text-muted fw-semibold">AOV benchmark</span>
                    across completed sales
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Highlights Row -->
<div class="row g-3 my-2">
    <!-- Sales Revenue Chart -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between border-bottom-0 pb-0">
                <h5 class="card-title mb-0">Monthly Sales Revenue</h5>
                <span class="badge bg-primary-subtle text-primary">Last 6 Months</span>
            </div>
            <div class="card-body">
                <div id="sales-overview-chart" style="height: 320px;"></div>
            </div>
        </div>
    </div>

    <!-- Order Statuses Distribution Donut -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom-0 pb-0">
                <h5 class="card-title mb-0">Fulfillment Status</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div id="order-status-donut" style="height: 240px;"></div>
                <div class="row text-center mt-3 g-2">
                    <div class="col-4">
                        <span class="fs-xs text-muted">Delivered</span>
                        <h6 class="fw-bold text-success mb-0">{{ $orderStatuses['delivered'] ?? 0 }}</h6>
                    </div>
                    <div class="col-4">
                        <span class="fs-xs text-muted">Shipped</span>
                        <h6 class="fw-bold text-info mb-0">{{ $orderStatuses['shipped'] ?? 0 }}</h6>
                    </div>
                    <div class="col-4">
                        <span class="fs-xs text-muted">Processing</span>
                        <h6 class="fw-bold text-warning mb-0">{{ $orderStatuses['processing'] ?? 0 }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders & Top Selling Products Row -->
<div class="row g-3 my-2">
    <!-- Recent Orders -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link text-decoration-none">View All Orders &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Fulfillment</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-primary">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-xs rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary fw-bold">
                                        {{ substr($order->customer_name, 0, 1) }}
                                    </span>
                                    <span>{{ $order->customer_name }}</span>
                                </div>
                            </td>
                            <td class="fs-xs text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="fw-bold">${{ number_format($order->grand_total, 2) }}</td>
                            <td><span class="{{ $order->payment_badge_class }}">{{ ucfirst($order->payment_status) }}</span></td>
                            <td><span class="{{ $order->fulfillment_badge_class }}">{{ ucfirst($order->fulfillment_status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light btn-icon" title="View Order">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No orders placed yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Selling Products & Low Stock Alerts -->
    <div class="col-xl-4">
        <!-- Top Products -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Top Selling Products</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-link text-decoration-none">Catalog &rarr;</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($topProducts as $prod)
                    <li class="list-group-item d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center gap-2 text-truncate me-2">
                            <img src="{{ $prod->main_image_url }}" alt="{{ $prod->name }}" class="rounded" width="40" height="40" style="object-fit: contain; background: #f8f9fa;">
                            <div class="text-truncate">
                                <a href="{{ route('admin.products.show', $prod) }}" class="text-dark fw-semibold text-truncate d-block" style="max-width: 180px;">
                                    {{ $prod->name }}
                                </a>
                                <span class="fs-xs text-muted">${{ number_format($prod->price, 2) }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark fw-bold">{{ $prod->total_sold }} sold</span>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-3">No sales data recorded.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 text-danger d-flex align-items-center gap-1">
                    <i class="ti ti-alert-triangle"></i> Low Stock Alerts
                </h5>
                <a href="{{ route('admin.inventory.index', ['filter' => 'low']) }}" class="btn btn-sm btn-link text-danger text-decoration-none">Inventory &rarr;</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($lowStockProducts as $lowProd)
                    <li class="list-group-item d-flex align-items-center justify-content-between py-2">
                        <div class="text-truncate me-2">
                            <a href="{{ route('admin.products.show', $lowProd) }}" class="fw-semibold text-dark d-block text-truncate" style="max-width: 200px;">
                                {{ $lowProd->name }}
                            </a>
                            <span class="fs-xs text-muted">SKU: {{ $lowProd->sku }}</span>
                        </div>
                        <span class="badge bg-danger-subtle text-danger fw-bold">Only {{ $lowProd->stock_qty }} left</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-3">All products are adequately stocked.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sales Line Chart
        var salesLabels = @json($chartLabels);
        var salesData = @json($chartSales);
        var ordersData = @json($chartOrders);

        var salesOptions = {
            series: [{
                name: 'Revenue ($)',
                type: 'column',
                data: salesData
            }, {
                name: 'Orders Count',
                type: 'line',
                data: ordersData
            }],
            chart: {
                height: 320,
                type: 'line',
                toolbar: { show: false }
            },
            stroke: {
                width: [0, 3],
                curve: 'smooth'
            },
            colors: ['#3b82f6', '#10b981'],
            labels: salesLabels,
            yaxis: [{
                title: { text: 'Revenue ($)' },
                labels: {
                    formatter: function (val) {
                        return '$' + Number(val).toLocaleString();
                    }
                }
            }, {
                opposite: true,
                title: { text: 'Orders' }
            }],
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };

        var chart = new ApexCharts(document.querySelector("#sales-overview-chart"), salesOptions);
        chart.render();

        // Fulfillment Donut Chart
        var orderStatuses = @json($orderStatuses);
        var donutLabels = Object.keys(orderStatuses).map(function(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        });
        var donutValues = Object.values(orderStatuses);

        var donutOptions = {
            series: donutValues.length > 0 ? donutValues : [1],
            labels: donutLabels.length > 0 ? donutLabels : ['Delivered'],
            chart: {
                type: 'donut',
                height: 240
            },
            colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#64748b'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            }
        };

        var donutChart = new ApexCharts(document.querySelector("#order-status-donut"), donutOptions);
        donutChart.render();
    });
</script>
@endpush
