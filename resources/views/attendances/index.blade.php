@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-header bg-transparent">
                    <div class="row g-3 align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Attendance Management</h5>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Mark Attendance
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3 g-3">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <input type="date" class="form-control form-control-sm" id="date_filter" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <select class="form-select form-select-sm" id="employee_filter">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <select class="form-select form-select-sm" id="status_filter">
                                <option value="">All Status</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half_day">Half Day</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <button class="btn btn-primary btn-sm w-100" onclick="filterAttendance()">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Working Hours</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d M, Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="recent-product-img">
                                                <img src="{{ $attendance->employee->photo ? asset('storage/'.$attendance->employee->photo) : asset('assets/images/avatars/avatar-1.png') }}" class="rounded-circle" width="32" alt="">
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-0 font-14">{{ $attendance->employee->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $attendance->check_in ? $attendance->check_in->format('h:i A') : '-' }}</td>
                                    <td>{{ $attendance->check_out ? $attendance->check_out->format('h:i A') : '-' }}</td>
                                    <td>
                                        @if($attendance->check_in && $attendance->check_out)
                                            @php
                                                $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                                $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                                $workingHours = $checkOut->diff($checkIn)->format('%H:%I');
                                            @endphp
                                            {{ $workingHours }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'present' => ['color' => 'success', 'icon' => 'check-circle'],
                                                'late' => ['color' => 'warning', 'icon' => 'clock-history'],
                                                'half_day' => ['color' => 'info', 'icon' => 'clock'],
                                                'absent' => ['color' => 'danger', 'icon' => 'x-circle']
                                            ];
                                            $status = $attendance->status;
                                            $config = $statusConfig[$status] ?? ['color' => 'secondary', 'icon' => 'question-circle'];
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ $config['color'] }} px-3 py-2">
                                            <i class="bi bi-{{ $config['icon'] }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;" title="{{ $attendance->notes ?? '' }}">
                                        {{ $attendance->notes ?? '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3 fs-6">
                                            <a href="{{ route('attendances.show', $attendance) }}" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('attendances.edit', $attendance) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            @if(!$attendance->check_out)
                                            <a href="javascript:;" class="text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Check Out" onclick="checkOut({{ $attendance->id }})">
                                                <i class="bi bi-box-arrow-right"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterAttendance() {
        const date = document.getElementById('date_filter').value;
        const employeeId = document.getElementById('employee_filter').value;
        const status = document.getElementById('status_filter').value;

        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        if (date) params.set('date', date);
        else params.delete('date');

        if (employeeId) params.set('employee_id', employeeId);
        else params.delete('employee_id');

        if (status) params.set('status', status);
        else params.delete('status');

        params.delete('page');

        window.location.href = url.pathname + '?' + params.toString();
    }

    function checkOut(attendanceId) {
        if(confirm('Are you sure you want to check out this attendance?')) {
            // Show loading indicator
            const btn = event.target.closest('a');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';

            fetch(`/attendances/${attendanceId}/check-out`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ _method: 'POST' })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Failed to check out');
                }
                return data;
            })
            .then(data => {
                const toast = new bootstrap.Toast(document.getElementById('liveToast'));
                document.getElementById('toastMessage').innerText = data.message;
                toast.show();
                setTimeout(() => window.location.reload(), 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                const toast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.getElementById('errorToastMessage').innerText = error.message;
                toast.show();
                btn.innerHTML = originalContent;
            });
        }
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>

    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastMessage"></div>
    </div>
</div>
@endsection
