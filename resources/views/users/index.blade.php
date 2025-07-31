@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Administration</div>
            <h2 class="page-title">User Management</h2>
        </div>
        <div class="col-auto ms-auto">
            <div class="btn-list">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add New User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Users</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm me-2" style="background-image: url({{ $user->getAvatarUrl() }})"></span>
                                        <div>
                                            <div>{{ $user->name }}</div>
                                            <div class="text-muted">{{ $user->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $roleColorMap = [
                                            'admin' => 'danger',
                                            'manager' => 'primary ',
                                            'salesman' => 'info',
                                            'accountant' => 'success',
                                            'inventory manager' => 'secondary',
                                            'guest' => 'warning'
                                        ];
                                        $role = strtolower($user->getPrimaryRole());
                                        $color = $roleColorMap[$role] ?? 'gray';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColorMap = [
                                            'active' => 'success',
                                            'inactive' => 'danger',
                                            'suspended' => 'secondary',
                                            'banned' => 'danger',
                                            'pending' => 'warning'
                                        ];
                                        $status = strtolower($user->status);
                                        $color = $statusColorMap[$status] ?? 'black';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                    {{-- <span class="badge rounded-pill bg-success">Success</span> --}}
                                </td>
                                <td>
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-icon" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                                <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                                <line x1="16" y1="5" x2="19" y2="8" />
                                            </svg>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon" onclick="return confirm('Are you sure?')" title="Delete">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center">
                <div class="pagination m-0 ms-auto">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
