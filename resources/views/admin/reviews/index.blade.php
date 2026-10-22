@extends('layouts.admin')

@section('title', 'Product Reviews & Ratings')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Customer Reviews & Ratings</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning-subtle text-warning fs-sm">
                    <i class="ti ti-star-filled text-warning"></i> Store Avg: {{ $avgRating }}/5.0
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-pills mb-3 gap-1">
    <li class="nav-item">
        <a class="nav-link {{ request('status') === null ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
            All Reviews <span class="badge bg-light text-dark ms-1">{{ $allCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('admin.reviews.index', ['status' => 'pending']) }}">
            Pending Moderation <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ route('admin.reviews.index', ['status' => 'approved']) }}">
            Approved <span class="badge bg-success-subtle text-success ms-1">{{ $approvedCount }}</span>
        </a>
    </li>
</ul>

<!-- Reviews Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">Item</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review Title & Feedback</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Moderation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $rev)
                <tr>
                    <td>
                        <img src="{{ $rev->product ? $rev->product->main_image_url : asset('assets/images/products/1.png') }}" alt="{{ $rev->product?->name }}" class="rounded" width="45" height="45" style="object-fit: contain; background: #f8f9fa;">
                    </td>
                    <td>
                        @if($rev->product)
                            <a href="{{ route('admin.products.show', $rev->product) }}" class="fw-semibold text-dark text-decoration-none">
                                {{ $rev->product->name }}
                            </a>
                        @else
                            <span class="text-muted">Deleted Product</span>
                        @endif
                    </td>
                    <td>
                        <strong class="text-dark">{{ $rev->customer?->full_name ?? 'Anonymous Buyer' }}</strong>
                    </td>
                    <td>
                        <div class="text-warning text-nowrap">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti ti-star{{ $i <= $rev->rating ? '-filled' : '' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>
                        <strong class="text-dark d-block">{{ $rev->title }}</strong>
                        <p class="text-muted fs-xs mb-0 text-truncate" style="max-width: 320px;">{{ $rev->body }}</p>
                    </td>
                    <td class="fs-xs text-muted">{{ $rev->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge {{ $rev->status === 'approved' ? 'bg-success' : ($rev->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ ucfirst($rev->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            @if($rev->status !== 'approved')
                            <form action="{{ route('admin.reviews.status', $rev) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-sm btn-outline-success btn-icon" title="Approve">
                                    <i class="ti ti-check"></i>
                                </button>
                            </form>
                            @endif

                            @if($rev->status !== 'rejected')
                            <form action="{{ route('admin.reviews.status', $rev) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-sm btn-outline-warning btn-icon" title="Reject">
                                    <i class="ti ti-ban"></i>
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" onsubmit="return confirm('Permanently remove this review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger btn-icon" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No reviews found matching current filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reviews->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center py-2">
        <span class="fs-xs text-muted">Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews</span>
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
