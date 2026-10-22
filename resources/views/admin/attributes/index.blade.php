@extends('layouts.admin')

@section('title', 'Attributes & Variants')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Product Attributes</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                <i class="ti ti-plus me-1"></i> Add Attribute
            </button>
        </div>
    </div>
</div>

<div class="row g-3">
    @forelse($attributes as $attr)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">{{ $attr->name }}</h5>
                    <span class="badge bg-light text-muted border fs-xs">Type: {{ ucfirst($attr->type) }}</span>
                </div>
                <form action="{{ route('admin.attributes.destroy', $attr) }}" method="POST" onsubmit="return confirm('Delete this attribute and all its values?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light text-danger btn-icon" title="Delete Attribute">
                        <i class="ti ti-trash"></i>
                    </button>
                </form>
            </div>
            <div class="card-body">
                <label class="form-label fs-xs text-muted mb-2">Available Values ({{ $attr->values->count() }}):</label>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @forelse($attr->values as $val)
                        <span class="badge bg-light text-dark border d-flex align-items-center gap-1 py-1 px-2">
                            @if($val->color_code)
                                <span class="rounded-circle d-inline-block border" style="width: 12px; height: 12px; background-color: {{ $val->color_code }};"></span>
                            @endif
                            <span>{{ $val->value }}</span>
                            <form action="{{ route('admin.attributes.values.destroy', $val) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this value?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-muted fs-xs ms-1 text-decoration-none" style="line-height: 1;">&times;</button>
                            </form>
                        </span>
                    @empty
                        <span class="text-muted fs-xs">No values added yet.</span>
                    @endforelse
                </div>

                <!-- Add Value Form -->
                <form action="{{ route('admin.attributes.values.store', $attr) }}" method="POST" class="pt-2 border-top">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="text" name="value" class="form-control" placeholder="Add value (e.g. XL, Red)" required>
                        @if($attr->type === 'color')
                            <input type="color" name="color_code" class="form-control form-control-color" value="#000000" title="Choose color" style="max-width: 45px;">
                        @endif
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="ti ti-plus"></i> Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 text-center text-muted">
            No attributes defined yet. Create your first attribute like "Size" or "Color".
        </div>
    </div>
    @endforelse
</div>

<!-- Create Attribute Modal -->
<div class="modal fade" id="createAttributeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.attributes.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Attribute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Attribute Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Size, Color, Storage" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="select">Dropdown Select</option>
                            <option value="button">Button / Pill Option</option>
                            <option value="color">Color Swatch</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Attribute</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
