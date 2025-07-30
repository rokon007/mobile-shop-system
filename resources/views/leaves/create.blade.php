@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Apply for Leave</h4>
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('leaves.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            @if(auth()->user()->hasRole('admin'))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                                    <select name="leave_type" id="leave_type" class="form-control @error('leave_type') is-invalid @enderror" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                        <option value="casual" {{ old('leave_type') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                        <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                        <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                        <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('leave_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason">Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Submit Leave Application
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Validate end date is after start date
        $('#end_date').change(function() {
            const startDate = new Date($('#start_date').val());
            const endDate = new Date($(this).val());
            
            if (endDate < startDate) {
                alert('End date cannot be before start date!');
                $(this).val('');
            }
        });
        
        $('#start_date').change(function() {
            const startDate = new Date($(this).val());
            const endDate = new Date($('#end_date').val());
            
            if ($('#end_date').val() && endDate < startDate) {
                alert('End date cannot be before start date!');
                $('#end_date').val('');
            }
        });
    });
</script>
@endpush
@endsection
