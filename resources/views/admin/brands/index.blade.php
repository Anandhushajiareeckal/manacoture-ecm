@extends('layouts.admin')

@section('title', 'Brands Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Brands</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                <i class="ti ti-plus me-1"></i> Add Brand
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">Logo</th>
                    <th>Brand Name</th>
                    <th>Slug</th>
                    <th>Website</th>
                    <th>Total Products</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td>
                        <span class="avatar-sm rounded bg-light border d-flex align-items-center justify-content-center p-1">
                            @if($brand->logo)
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" height="20">
                            @else
                                <i class="ti ti-tag text-muted"></i>
                            @endif
                        </span>
                    </td>
                    <td>
                        <strong class="text-dark">{{ $brand->name }}</strong>
                        @if($brand->description)
                            <div class="fs-xs text-muted text-truncate" style="max-width: 250px;">{{ $brand->description }}</div>
                        @endif
                    </td>
                    <td><code>{{ $brand->slug }}</code></td>
                    <td>
                        @if($brand->website)
                            <a href="{{ $brand->website }}" target="_blank" class="text-muted fs-xs">
                                {{ parse_url($brand->website, PHP_URL_HOST) ?? $brand->website }} <i class="ti ti-external-link fs-xs"></i>
                            </a>
                        @else
                            <span class="text-muted fs-xs">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.index', ['brand_id' => $brand->id]) }}" class="badge bg-primary-subtle text-primary">
                            {{ $brand->products_count }} products
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $brand->id }}" title="Edit">
                            <i class="ti ti-edit"></i>
                        </button>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this brand?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger btn-icon" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Brand Modal -->
                <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.brands.update', $brand) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Brand: {{ $brand->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Official Website</label>
                                        <input type="url" name="website" class="form-control" value="{{ $brand->website }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $brand->description }}</textarea>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="brandActive{{ $brand->id }}" value="1" {{ $brand->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="brandActive{{ $brand->id }}">Active</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No brands found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Brand Modal -->
<div class="modal fade" id="createBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Nike, Apple" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Official Website</label>
                        <input type="url" name="website" class="form-control" placeholder="https://brand.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brand overview..."></textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="createBrandActive" value="1" checked>
                        <label class="form-check-label" for="createBrandActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Brand</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
