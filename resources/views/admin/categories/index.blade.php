@extends('layouts.admin')

@section('title', 'Categories Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Product Categories</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="ti ti-plus me-1"></i> Add Category
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">Icon</th>
                    <th>Category Name</th>
                    <th>Slug</th>
                    <th>Parent Category</th>
                    <th>Total Products</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <span class="avatar-xs rounded bg-light d-flex align-items-center justify-content-center text-primary">
                            <i class="ti ti-folder"></i>
                        </span>
                    </td>
                    <td>
                        <strong class="text-dark">{{ $category->name }}</strong>
                        @if($category->description)
                            <div class="fs-xs text-muted text-truncate" style="max-width: 250px;">{{ $category->description }}</div>
                        @endif
                    </td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>
                        @if($category->parent)
                            <span class="badge bg-light text-dark border">{{ $category->parent->name }}</span>
                        @else
                            <span class="text-muted fs-xs">Root Category</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="badge bg-primary-subtle text-primary">
                            {{ $category->products_count }} products
                        </a>
                    </td>
                    <td>{{ $category->sort_order }}</td>
                    <td>
                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light btn-icon" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}" title="Edit">
                            <i class="ti ti-edit"></i>
                        </button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger btn-icon" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Category: {{ $category->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Parent Category</label>
                                        <select name="parent_id" class="form-select">
                                            <option value="">None (Top-Level)</option>
                                            @foreach($parentCategories as $pCat)
                                                @if($pCat->id !== $category->id)
                                                    <option value="{{ $pCat->id }}" {{ $category->parent_id == $pCat->id ? 'selected' : '' }}>{{ $pCat->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $category->description }}</textarea>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Sort Order</label>
                                            <input type="number" name="sort_order" class="form-control" value="{{ $category->sort_order }}" min="0">
                                        </div>
                                        <div class="col-6 d-flex align-items-center pt-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="active{{ $category->id }}" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active{{ $category->id }}">Active</label>
                                            </div>
                                        </div>
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
                    <td colspan="8" class="text-center py-4 text-muted">No categories created yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Footwear & Shoes" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (Top-Level Category)</option>
                            @foreach($parentCategories as $pCat)
                                <option value="{{ $pCat->id }}">{{ $pCat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-6 d-flex align-items-center pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="createActive" value="1" checked>
                                <label class="form-check-label" for="createActive">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
