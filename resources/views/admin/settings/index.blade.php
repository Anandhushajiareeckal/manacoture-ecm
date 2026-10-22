@extends('layouts.admin')

@section('title', 'Store Settings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Store & System Settings</h4>
        </div>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    <div class="row g-3">
        <!-- General Store Info -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0"><i class="ti ti-building-store me-1 text-primary"></i> General Store Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Store Brand Name</label>
                        <input type="text" name="store_name" class="form-control" value="{{ \App\Models\Setting::get('store_name', 'INSPINIA Commerce') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Support Email Address</label>
                        <input type="email" name="store_email" class="form-control" value="{{ \App\Models\Setting::get('store_email', 'support@inspinia.com') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Customer Contact Phone</label>
                        <input type="text" name="store_phone" class="form-control" value="{{ \App\Models\Setting::get('store_phone', '+1 (555) 789-0123') }}">
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Store Physical Address</label>
                        <textarea name="store_address" rows="3" class="form-control">{{ \App\Models\Setting::get('store_address', '742 Evergreen Terrace, Springfield, OR 97477') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency & Tax & Logistics -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0"><i class="ti ti-coin me-1 text-success"></i> Currency & Taxation</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="{{ \App\Models\Setting::get('currency_symbol', '$') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Currency Code (ISO)</label>
                            <input type="text" name="currency_code" class="form-control" value="{{ \App\Models\Setting::get('currency_code', 'USD') }}">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Default Sales Tax Rate (%)</label>
                        <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ \App\Models\Setting::get('tax_rate', '8.25') }}">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0"><i class="ti ti-archive me-1 text-warning"></i> Inventory & Automation</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Default Low Stock Alert Threshold</label>
                        <input type="number" name="low_stock_threshold" class="form-control" value="{{ \App\Models\Setting::get('low_stock_threshold', '5') }}" min="1">
                        <div class="form-text">Triggers warning flags when any product inventory drops to this amount.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary fw-semibold px-4 py-2">
                <i class="ti ti-device-floppy me-1"></i> Save Store Settings
            </button>
        </div>
    </div>
</form>
@endsection
