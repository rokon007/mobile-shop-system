@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Role</h4>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm float-end">
                        <i class="fas fa-arrow-left"></i> Back to Roles
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $role->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guard_name" class="form-label">Guard Name</label>
                                    <input type="text" class="form-control" id="guard_name" name="guard_name"
                                           value="{{ $role->guard_name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Permissions</label>
                                    <div class="row">
                                        @foreach($permissions->groupBy(function($permission) {
                                            return explode(' ', $permission->name)[1] ?? 'general';
                                        }) as $group => $groupPermissions)
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">{{ ucfirst($group) }} Permissions</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach($groupPermissions as $permission)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="permissions[]" value="{{ $permission->id }}"
                                                                       id="permission_{{ $permission->id }}"
                                                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Role
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
