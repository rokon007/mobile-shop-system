<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with(['employee'])->latest()->paginate(10);
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        Leave::create($request->all());

        return redirect()->route('leaves.index')->with('success', 'Leave application created successfully');
    }

    public function show(Leave $leave)
    {
        $leave->load(['employee']);
        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $employees = Employee::all();
        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $leave->update($request->all());

        return redirect()->route('leaves.index')->with('success', 'Leave application updated successfully');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave application deleted successfully');
    }

    public function approve(Leave $leave)
    {
        $leave->update(['status' => 'approved']);
        return back()->with('success', 'Leave application approved successfully');
    }

    public function reject(Leave $leave)
    {
        $leave->update(['status' => 'rejected']);
        return back()->with('success', 'Leave application rejected successfully');
    }
}
