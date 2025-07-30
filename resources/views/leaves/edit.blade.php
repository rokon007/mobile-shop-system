@extends('layouts.app')

@section('title', 'Edit Leave Application')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h3>Edit Leave Application</h3>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('leaves.show', $leave) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View Details
        </a>
    </div>
</div>

<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <form action="{{ route('leaves.update', $leave) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee_id">Employee <span class="text-danger">*</span></label>
                            <select class="form-control @error('employee_id') is-invalid @enderror" 
                                    id="employee_id" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $leave->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('leave_type') is-invalid @enderror" 
                                    id="leave_type" name="leave_type" required>
                                <option value="">Select Leave Type</option>
                                <option value="sick" {{ old('leave_type', $leave->leave_type) == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="casual" {{ old('leave_type', $leave->leave_type) == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                <option value="annual" {{ old('leave_type', $leave->leave_type) == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                <option value="maternity" {{ old('leave_type', $leave->leave_type) == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                <option value="paternity" {{ old('leave_type', $leave->leave_type) == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                                <option value="emergency" {{ old('leave_type', $leave->leave_type) == 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                            </select>
                            @error('leave_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', $leave->start_date) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date', $leave->end_date) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reason">Reason <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('reason') is-invalid @enderror" 
                              id="reason" name="reason" rows="4" required>{{ old('reason', $leave->reason) }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="contact_during_leave">Contact During Leave</label>
                    <input type="text" class="form-control @error('contact_during_leave') is-invalid @enderror" 
                           id="contact_during_leave" name="contact_during_leave" value="{{ old('contact_during_leave', $leave->contact_during_leave) }}">
                    @error('contact_during_leave')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Leave Application
                    </button>
                    <a href="{{ route('leaves.show', $leave) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    endDateInput.min = startDate;
    if (endDateInput.value && endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
});
</script>
@endsection
