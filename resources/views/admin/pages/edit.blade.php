@extends('layouts.admin')

@section('title', 'Edit Page — ' . $page->title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Edit CMS Page: {{ $page->title }}</h4>
                <p class="text-muted fs-xs mb-0">Modify page content, SEO metadata, and publication status.</p>
            </div>
            <div class="d-flex gap-2">
                @if($page->is_active)
                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-outline-info">
                        <i class="ti ti-external-link me-1"></i> View Live Page
                    </a>
                @endif
                <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to All Pages
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Page Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $page->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label fw-semibold">URL Slug <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">/pages/</span>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $page->slug) }}" required>
                        </div>
                        @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label fw-semibold">Brief Summary / Excerpt</label>
                        <textarea name="excerpt" id="excerpt" rows="2" class="form-control">{{ old('excerpt', $page->excerpt) }}</textarea>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="content" class="form-label fw-semibold mb-0">Page Content (HTML Supported) <span class="text-danger">*</span></label>
                            <span class="fs-xs text-muted">Supports &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;blockquote&gt;</span>
                        </div>
                        <textarea name="content" id="content" rows="15" class="form-control font-monospace @error('content') is-invalid @enderror" required>{{ old('content', $page->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Search Engine Optimization (SEO)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label fw-semibold">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                    </div>
                    <div class="mb-0">
                        <label for="meta_description" class="form-label fw-semibold">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" class="form-control">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Publishing Controls -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">Publishing</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Page is Published (Live)</label>
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label fw-semibold">Display Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $page->sort_order) }}" min="0">
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between text-muted fs-xs mb-1">
                            <span>Created:</span>
                            <span>{{ $page->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted fs-xs mb-3">
                            <span>Last Updated:</span>
                            <span>{{ $page->updated_at->diffForHumans() }}</span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-semibold py-2">
                                <i class="ti ti-check me-1"></i> Update Page
                            </button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
