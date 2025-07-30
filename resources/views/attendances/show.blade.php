@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Attendance Details</h4>
                    <div class="float-end">
                        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Employee:</th>
                                    <td>{{ $attendance->employee->name }} ({{ $attendance->employee->employee_id }})</td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Check In:</th>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Check Out:</th>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge bg-success">Present</span>
                                                @break
                                            @case('absent')
                                                <span class="badge bg-danger">Absent</span>
                                                @break
                                            @case('late')
                                                <span class="badge bg-warning">Late</span>
                                                @break
                                            @case('half_day')
                                                <span class="badge bg-info">Half Day</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($attendance->status) }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Working Hours:</th>
                                    <td>
                                        @if($attendance->check_in && $attendance->check_out)
                                            {{ $attendance->check_in->diffInHours($attendance->check_out) }} hours
                                            {{ $attendance->check_in->diffInMinutes($attendance->check_out) % 60 }} minutes
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Overtime Hours:</th>
                                    <td>{{ $attendance->overtime_hours ?? 0 }} hours</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ $attendance->employee->department ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td>{{ $attendance->employee->position ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $attendance->created_at->format('d M Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($attendance->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Notes:</h6>
                            <div class="alert alert-info">
                                {{ $attendance->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
