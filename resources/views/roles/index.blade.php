@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">User Management</div>
            <h3 class="page-title">Roles & Permissions</h3>
        </div>
        <div class="col-auto ms-auto">
            <div class="btn-list">
                <a href="{{ route('roles.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add New Role
                </a>
                <a href="{{ route('roles.create') }}" class="btn btn-primary d-sm-none btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">All Roles</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
                                <th>Permissions</th>
                                <th>Users</th>
                                <th>Created</th>
                                <th class="w-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                            <tr>
                                <td class="text-muted">{{ $role->id }}</td>
                                <td>
                                    @php
                                        $roleColorMap = [
                                            'admin' => 'danger',
                                            'manager' => 'primary',
                                            'salesman' => 'info',
                                            'accountant' => 'success',
                                            'inventory manager' => 'secondary',
                                            'guest' => 'warning'
                                        ];
                                        $roleName = strtolower($role->name);
                                        $color = $roleColorMap[$roleName] ?? 'dark';
                                    @endphp
                                    <span class="badge bg-{{ $color }} text-{{ $color }}-fg">
                                        {{ $role->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($role->permissions->take(3) as $permission)
                                            <span class="badge bg-primary">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 3)
                                            <span class="badge bg-info">+{{ $role->permissions->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $role->users->count() }} users</span>
                                </td>
                                <td class="text-muted">
                                    {{ $role->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-icon" aria-label="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="2" />
                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-icon" aria-label="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                                <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                                <line x1="16" y1="5" x2="19" y2="8" />
                                            </svg>
                                        </a>
                                        @if($role->name !== 'Super Admin')
                                        <form method="POST" action="{{ route('roles.destroy', $role) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon" onclick="return confirm('Are you sure you want to delete this role?')" aria-label="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="empty">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="12" cy="12" r="9" />
                                                <line x1="9" y1="10" x2="9.01" y2="10" />
                                                <line x1="15" y1="10" x2="15.01" y2="10" />
                                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" />
                                            </svg>
                                        </div>
                                        <p class="empty-title">No roles found</p>
                                        <p class="empty-subtitle text-muted">
                                            Create your first role by clicking the "Add New Role" button
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">Showing <span>{{ $roles->firstItem() }}</span> to <span>{{ $roles->lastItem() }}</span> of <span>{{ $roles->total() }}</span> entries</p>
                <div class="ms-auto">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
