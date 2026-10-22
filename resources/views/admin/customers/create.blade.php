@extends('layouts.admin')

@section('title', 'Add Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Add New Customer Account</h4>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Customers
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.customers.store') }}" method="POST">
    @csrf
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="col-12"><hr class="my-2"></div>
                <h6 class="fw-bold mb-0">Initial Shipping Address (Optional)</h6>

                <div class="col-12">
                    <label class="form-label">Street Address</label>
                    <input type="text" name="address_line1" class="form-control" placeholder="123 Main St, Apt 4B">
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" placeholder="San Francisco">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State / Region</label>
                    <input type="text" name="state" class="form-control" placeholder="CA">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Postal / Zip Code</label>
                    <input type="text" name="postal_code" class="form-control" placeholder="94105">
                </div>

                <div class="col-12">
                    <label class="form-label">Internal Admin Notes</label>
                    <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i> Create Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
