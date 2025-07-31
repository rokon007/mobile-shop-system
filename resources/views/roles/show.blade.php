@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Role Details</h4>
                    <div class="float-end">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Role Name:</th>
                                    <td>{{ $role->name }}</td>
                                </tr>
                                <tr>
                                    <th>Guard Name:</th>
                                    <td>{{ $role->guard_name }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $role->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $role->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Users with this Role</h5>
                            @if($role->users->count() > 0)
                                <ul class="list-group">
                                    @foreach($role->users as $user)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $user->name }}
                                            <span class="badge bg-primary rounded-pill">{{ $user->email }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No users assigned to this role.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Permissions</h5>
                            @if($role->permissions->count() > 0)
                                <div class="row">
                                    @foreach($role->permissions->groupBy(function($permission) {
                                        return explode(' ', $permission->name)[1] ?? 'general';
                                    }) as $group => $groupPermissions)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">{{ ucfirst($group) }} Permissions</h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($groupPermissions as $permission)
                                                        <span class="badge bg-success me-1 mb-1">{{ $permission->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No permissions assigned to this role.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
