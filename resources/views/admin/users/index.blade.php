@extends('layouts.admin')

@section('title', 'Staff & User Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Administrative Staff & Roles</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="ti ti-user-plus me-1"></i> Add Staff Member
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">Avatar</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Assigned Role</th>
                    <th>Last Active</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    </td>
                    <td>
                        <strong class="text-dark">{{ $user->name }}</strong>
                        @if($user->id === auth()->id())
                            <span class="badge bg-primary-subtle text-primary fs-xs ms-1">You</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary fs-xs">
                            {{ $user->role?->name ?? 'Staff' }}
                        </span>
                    </td>
                    <td class="fs-xs text-muted">
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                    </td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove access for this staff user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger btn-icon" title="Remove User">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                        @else
                            <span class="text-muted fs-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Administrative Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="john@inspinia.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }} - {{ $role->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Temporary Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" minlength="8" required placeholder="Min 8 characters">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="userActive" value="1" checked>
                        <label class="form-check-label" for="userActive">Account Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Staff Account</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
