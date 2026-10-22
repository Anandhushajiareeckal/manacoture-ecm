@extends('layouts.admin')

@section('title', 'Edit Customer - ' . $customer->full_name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Edit Customer: {{ $customer->full_name }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-eye me-1"></i> View Profile
                </a>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Back to Customers
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.customers.update', $customer) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Internal Notes</label>
                    <textarea name="notes" rows="3" class="form-control">{{ old('notes', $customer->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="custActive" value="1" {{ $customer->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="custActive">Account Active</label>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i> Update Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
