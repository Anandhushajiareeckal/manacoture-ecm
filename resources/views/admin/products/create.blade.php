@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Create New Product</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Back to Catalog
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <!-- Main Product Information -->
        <div class="col-lg-8">
            <!-- Basic Details -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">General Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Sony WH-1000XM5 Headphones" required>
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Summary</label>
                        <textarea name="short_description" id="short_description" rows="2" class="form-control" placeholder="Brief 1-2 sentence pitch...">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Product Description</label>
                        <textarea name="description" id="description" rows="6" class="form-control" placeholder="Detailed product specifications, features, and materials...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="price" class="form-label">Selling Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="999999999999.99" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label for="cost_price" class="form-label">Cost Price (₹)</label>
                            <input type="number" step="0.01" min="0" max="999999999999.99" name="cost_price" id="cost_price" class="form-control" value="{{ old('cost_price') }}" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label for="compare_price" class="form-label">Compare-at Price (₹)</label>
                            <input type="number" step="0.01" min="0" max="999999999999.99" name="compare_price" id="compare_price" class="form-control" value="{{ old('compare_price') }}" placeholder="0.00">
                        </div>

                        <div class="col-md-4">
                            <label for="sku" class="form-label">SKU (Barcode) <span class="text-danger">*</span></label>
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="e.g. SNY-WH-01" required>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_qty" class="form-label">Initial Stock Qty <span class="text-danger">*</span></label>
                            <input type="number" name="stock_qty" id="stock_qty" class="form-control @error('stock_qty') is-invalid @enderror" value="{{ old('stock_qty', 10) }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label for="low_stock_threshold" class="form-label">Low Stock Alert <span class="text-danger">*</span></label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', 5) }}" min="0" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Couture Details -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Couture Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fabric" class="form-label">Fabric / Material</label>
                            <input type="text" name="fabric" id="fabric" class="form-control" value="{{ old('fabric') }}" placeholder="e.g. Pure Kanchi Cotton, Mulberry Silk">
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="form-label">Color / Palette</label>
                            <input type="text" name="color" id="color" class="form-control" value="{{ old('color') }}" placeholder="e.g. Ivory & Gold, Cream & Maroon">
                        </div>
                        <div class="col-md-6">
                            <label for="occasion" class="form-label">Occasion</label>
                            <input type="text" name="occasion" id="occasion" class="form-control" value="{{ old('occasion') }}" placeholder="e.g. Festival, Wedding, Temple">
                        </div>
                        <div class="col-md-6">
                            <label for="badge" class="form-label">Promotional Badge</label>
                            <input type="text" name="badge" id="badge" class="form-control" value="{{ old('badge') }}" placeholder="e.g. Best Seller, Bridal, Handloom">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Upload -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Product Image</h5>
                </div>
                <div class="card-body">
                    <label for="main_image" class="form-label">Main Display Image</label>
                    <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*">
                    <div class="form-text">Recommended size 800x800px. Supports PNG, JPG, WebP.</div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="col-lg-4">
            <!-- Organization -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Organization</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active (Visible in store)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Product Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="simple" {{ old('type') === 'simple' ? 'selected' : '' }}>Simple Product</option>
                            <option value="variable" {{ old('type') === 'variable' ? 'selected' : '' }}>Variable Product</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">None / Unassigned</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">None / Unassigned</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_featured">Feature on Storefront</label>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent">
                    <h5 class="card-title mb-0">Search Engine Optimization</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Page Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="SEO title">
                    </div>
                    <div class="mb-0">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" class="form-control" placeholder="Meta description snippet...">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="card border-0 shadow-sm">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                        <i class="ti ti-check me-1"></i> Save & Publish Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light">Discard</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
