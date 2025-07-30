@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Employee Management</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Employee
        </a>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <!-- Filter Form -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search employees..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="department">
                            <option value="">All Departments</option>
                            <option value="Sales" {{ request('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Inventory" {{ request('department') == 'Inventory' ? 'selected' : '' }}>Inventory</option>
                            <option value="Accounts" {{ request('department') == 'Accounts' ? 'selected' : '' }}>Accounts</option>
                            <option value="Management" {{ request('department') == 'Management' ? 'selected' : '' }}>Management</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Employee ID</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Phone</th>
                            <th>Salary</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($employee->photo)
                                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}" 
                                             class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            {{ substr($employee->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $employee->name }}</strong>
                                        <br><small class="text-muted">{{ $employee->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>৳{{ number_format($employee->salary, 2) }}</td>
                            <td>{{ $employee->join_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $employee->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-info btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3,6 5,6 21,6"></polyline><path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No employees found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
            <div class="d-flex justify-content-center">
                {{ $employees->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
