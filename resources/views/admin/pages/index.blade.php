@extends('layouts.admin')

@section('title', 'Custom CMS Pages')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Custom CMS Pages</h4>
                <p class="text-muted fs-xs mb-0">Create and publish policies, delivery guides, certificates, and story pages dynamically.</p>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary fw-semibold">
                <i class="ti ti-plus me-1"></i> Create New Page
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">Order</th>
                        <th>Page Title</th>
                        <th>Storefront URL Slug</th>
                        <th>Excerpt</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td class="ps-3 text-muted fw-semibold">{{ $page->sort_order }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $page->title }}</div>
                                <small class="text-muted">Updated {{ $page->updated_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <code class="text-primary bg-light px-2 py-1 rounded">/pages/{{ $page->slug }}</code>
                            </td>
                            <td class="text-muted text-truncate" style="max-width: 280px;">
                                {{ $page->excerpt ?: Str::limit(strip_tags($page->content), 80) }}
                            </td>
                            <td>
                                @if($page->is_active)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="ti ti-circle-check me-1"></i> Published
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="ti ti-clock me-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-light" title="View Live">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                    <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-light" title="Toggle Publish">
                                            <i class="ti ti-toggle-{{ $page->is_active ? 'right text-success' : 'left text-muted' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-light" title="Edit Page">
                                        <i class="ti ti-edit text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light text-danger" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="ti ti-file-text fs-1 d-block mb-2 text-secondary"></i>
                                No custom CMS pages found. Click "Create New Page" to add your first page.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
