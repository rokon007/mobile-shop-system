@extends('layouts.app')

@section('title', 'Leave Application Details')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Leave Application Details</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        @if($leave->status == 'pending')
            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this leave?')">
                    <i class="fas fa-check"></i> Approve
                </button>
            </form>
            <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this leave?')">
                    <i class="fas fa-times"></i> Reject
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-8 col-lg-8 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Employee:</strong></label>
                        <p>{{ $leave->employee->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Leave Type:</strong></label>
                        <p>{{ ucfirst($leave->leave_type) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Start Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>End Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label><strong>Duration:</strong></label>
                <p>{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} day(s)</p>
            </div>
            
            <div class="form-group">
                <label><strong>Status:</strong></label>
                <span class="badge badge-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($leave->status) }}
                </span>
            </div>
            
            <div class="form-group">
                <label><strong>Reason:</strong></label>
                <p>{{ $leave->reason }}</p>
            </div>
            
            @if($leave->contact_during_leave)
            <div class="form-group">
                <label><strong>Contact During Leave:</strong></label>
                <p>{{ $leave->contact_during_leave }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-4 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <h5>Leave Information</h5>
            <div class="list-group">
                <div class="list-group-item">
                    <strong>Applied On:</strong><br>
                    {{ $leave->created_at->format('M d, Y h:i A') }}
                </div>
                <div class="list-group-item">
                    <strong>Last Updated:</strong><br>
                    {{ $leave->updated_at->format('M d, Y h:i A') }}
                </div>
                <div class="list-group-item">
                    <strong>Employee Department:</strong><br>
                    {{ $leave->employee->department ?? 'N/A' }}
                </div>
                <div class="list-group-item">
                    <strong>Employee Position:</strong><br>
                    {{ $leave->employee->position ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
