@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="card p-4 shadow-sm border-0">
    <div class="text-center mb-3">
        <h4 class="fw-bold">Welcome Back</h4>
        <p class="text-muted">Sign in to your E-Commerce Admin Portal</p>
    </div>

    <!-- Flash message or errors -->
    @if ($errors->any())
        <div class="alert alert-danger py-2 fs-sm">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', 'admin@inspinia.com') }}" required autofocus />
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label mb-0">Password <span class="text-danger">*</span></label>
            </div>
            <div class="input-group mt-1">
                <span class="input-group-text"><i class="ti ti-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="password" required />
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" checked />
                <label class="form-check-label fs-sm" for="remember">Remember me</label>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary fw-semibold py-2">
                <i class="ti ti-login me-1"></i> Sign In to Dashboard
            </button>
        </div>

        <!-- Quick Demo Credentials Info Card -->
        <div class="card bg-light border-0 mb-0">
            <div class="card-body p-2 fs-xs text-muted">
                <div class="fw-bold text-dark mb-1"><i class="ti ti-info-circle me-1 text-primary"></i> Demo Login Accounts:</div>
                <div><strong>Super Admin:</strong> <code>admin@inspinia.com</code> / <code>password</code></div>
                <div><strong>Manager:</strong> <code>manager@inspinia.com</code> / <code>password</code></div>
            </div>
        </div>
    </form>
</div>
@endsection
