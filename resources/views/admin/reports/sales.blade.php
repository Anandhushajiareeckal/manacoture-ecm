@extends('layouts.admin')

@section('title', 'Sales Analytics & Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Sales Analytics Report</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.export-orders') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-download me-1"></i> Export Orders CSV
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter Form -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <label class="form-label fs-xs text-muted mb-1">From Date</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fs-xs text-muted mb-1">To Date</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="ti ti-filter me-1"></i> Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Financial Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Net Paid Revenue</span>
                <h3 class="fw-bold text-success mb-0 mt-1">${{ number_format($totalSales, 2) }}</h3>
                <span class="fs-xs text-muted">{{ $totalOrdersCount }} completed orders</span>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Tax Collected</span>
                <h4 class="fw-bold text-dark mb-0 mt-1">${{ number_format($totalTax, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Shipping Revenue</span>
                <h4 class="fw-bold text-dark mb-0 mt-1">${{ number_format($totalShipping, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Discounts Given</span>
                <h4 class="fw-bold text-danger mb-0 mt-1">${{ number_format($totalDiscounts, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <span class="text-muted fs-xs fw-semibold">Average Ticket</span>
                <h4 class="fw-bold text-primary mb-0 mt-1">
                    ${{ $totalOrdersCount > 0 ? number_format($totalSales / $totalOrdersCount, 2) : '0.00' }}
                </h4>
            </div>
        </div>
    </div>
</div>

<!-- Sales by Payment Gateway -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="card-title mb-0">Sales Breakdown by Payment Gateway</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Payment Method</th>
                    <th>Number of Orders</th>
                    <th>Volume Share</th>
                    <th class="text-end">Total Revenue ($)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesByPayment as $sp)
                <tr>
                    <td>
                        <strong class="text-dark">{{ $sp->payment_method ?? 'Other / Direct' }}</strong>
                    </td>
                    <td>{{ $sp->count }} orders</td>
                    <td>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalSales > 0 ? ($sp->total / $totalSales) * 100 : 0 }}%;"></div>
                        </div>
                    </td>
                    <td class="text-end fw-bold">${{ number_format($sp->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No sales recorded for the selected period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
