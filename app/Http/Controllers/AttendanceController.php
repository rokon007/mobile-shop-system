<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['employee'])->latest()->paginate(15);
        $employees = Employee::all();
        return view('attendances.index', compact('attendances', 'employees'));
    }

    public function checkOut(Attendance $attendance)
    {
        try {
            if ($attendance->check_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee already checked out at ' . $attendance->check_out
                ], 400);
            }

            $checkOutTime = now()->format('H:i'); // Use H:i format
            $checkInTime = $attendance->check_in;

            // Normalize time formats (handle both H:i and H:i:s)
            $checkInNormalized = $this->normalizeTime($checkInTime);
            $checkOutNormalized = $this->normalizeTime($checkOutTime);

            $workingHours = $this->calculateWorkingHours($checkInNormalized, $checkOutNormalized);

            $attendance->update([
                'check_out' => $checkOutTime,
                'working_hours' => $workingHours,
                'status' => $this->determineStatus($checkInNormalized, $checkOutNormalized)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Checked out successfully at ' . $checkOutTime,
                'working_hours' => $workingHours
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function normalizeTime($time)
    {
        // Handle cases where time might be in H:i or H:i:s format
        if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
            return $time . ':00'; // Add seconds if missing
        }
        return $time;
    }

    private function calculateWorkingHours($checkIn, $checkOut)
    {
        try {
            // Try with seconds first
            try {
                $start = \Carbon\Carbon::createFromFormat('H:i:s', $checkIn);
                $end = \Carbon\Carbon::createFromFormat('H:i:s', $checkOut);
                return $start->diff($end)->format('%H:%I');
            } catch (\Exception $e) {
                // Fallback to without seconds
                $start = \Carbon\Carbon::createFromFormat('H:i', substr($checkIn, 0, 5));
                $end = \Carbon\Carbon::createFromFormat('H:i', substr($checkOut, 0, 5));
                return $start->diff($end)->format('%H:%I');
            }
        } catch (\Exception $e) {
            // Ultimate fallback to simple calculation
            $start = strtotime($checkIn);
            $end = strtotime($checkOut);
            $diff = $end - $start;
            return gmdate('H:i', $diff);
        }
    }

    private function determineStatus($checkIn, $checkOut)
    {
        $checkInTime = strtotime($checkIn);
        $checkOutTime = strtotime($checkOut);
        $officeStart = strtotime('09:00:00');

        if ($checkInTime > $officeStart + 1800) { // 30 minutes late
            return 'late';
        }

        $workedHours = ($checkOutTime - $checkInTime) / 3600;
        if ($workedHours < 4) {
            return 'half_day';
        }

        return 'present';
    }

    public function create()
    {
        $employees = Employee::all();
        return view('attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'nullable|after:check_in',
            'status' => 'required|in:present,absent,late,half_day',
        ]);

        Attendance::create($request->all());

        return redirect()->route('attendances.index')->with('success', 'Attendance recorded successfully');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['employee']);
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::all();
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'nullable|after:check_in',
            'status' => 'required|in:present,absent,late,half_day',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'Attendance updated successfully');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Attendance deleted successfully');
    }
}
