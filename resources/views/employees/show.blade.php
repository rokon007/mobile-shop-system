@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Employee Details</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
                <i class="fa fa-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; font-size: 60px; color: white;">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                            @endif
                            <h3 class="mt-3">{{ $employee->name }}</h3>
                            <p class="text-muted">{{ $employee->position }}</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Employee ID</h5>
                                    <p>{{ $employee->employee_id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Department</h5>
                                    <p>{{ $employee->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Email</h5>
                                    <p>{{ $employee->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Phone</h5>
                                    <p>{{ $employee->phone }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Date of Birth</h5>
                                    <p>{{ $employee->date_of_birth ? date('d M, Y', strtotime($employee->date_of_birth)) : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Gender</h5>
                                    <p>{{ ucfirst($employee->gender) ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Join Date</h5>
                                    <p>{{ date('d M, Y', strtotime($employee->join_date)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Salary</h5>
                                    <p>৳{{ number_format($employee->salary, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Status</h5>
                                    <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="info-item">
                                    <h5>Address</h5>
                                    <p>{{ $employee->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <h5>Emergency Contact</h5>
                                    <p>{{ $employee->emergency_contact_name ?? 'N/A' }}</p>
                                    <p>{{ $employee->emergency_contact_phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .info-item {
        margin-bottom: 20px;
    }
    .info-item h5 {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .info-item p {
        font-size: 16px;
        margin-bottom: 0;
    }
</style>
@endsection
