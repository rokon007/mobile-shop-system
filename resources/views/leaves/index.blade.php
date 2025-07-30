@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Leave Management</h4>
                    <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Apply for Leave
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->id }}</td>
                                    <td>{{ $leave->employee->name }}</td>
                                    <td>{{ ucfirst($leave->leave_type) }}</td>
                                    <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                                    <td>{{ $leave->days }}</td>
                                    <td>{{ Str::limit($leave->reason, 50) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($leave->status == 'pending')
                                        <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $leaves->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
