<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['employee', 'createdBy', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $categories = [
            'salary_payment' => 'Salary Payment',
            'utility_expense' => 'Utility Expense',
            'shop_rent' => 'Shop Rent',
            'daily_expense' => 'Daily Expense',
            'monthly_expense' => 'Monthly Expense',
            'yearly_expense' => 'Yearly Expense',
            'marketing' => 'Marketing',
            'maintenance' => 'Maintenance',
            'transport' => 'Transport',
            'other' => 'Other'
        ];

        return view('expenses.create', compact('employees', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = Auth::id();

            //Handle file upload
            if ($request->hasFile('receipt_file')) {
                $data['receipt_file'] = $request->file('receipt_file')->store('receipts', 'public');
            }

            Expense::create($data);

            return redirect()->route('expenses.index')->with('success', 'Expense created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating expense: ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        $expense->load(['employee', 'creator', 'approver']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $employees = Employee::where('status', 'active')->get();
        $categories = [
            'salary_payment' => 'Salary Payment',
            'utility_expense' => 'Utility Expense',
            'shop_rent' => 'Shop Rent',
            'daily_expense' => 'Daily Expense',
            'monthly_expense' => 'Monthly Expense',
            'yearly_expense' => 'Yearly Expense',
            'marketing' => 'Marketing',
            'maintenance' => 'Maintenance',
            'transport' => 'Transport',
            'other' => 'Other'
        ];

        return view('expenses.edit', compact('expense', 'employees', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $data = $request->all();

            // Handle file upload
            if ($request->hasFile('receipt_file')) {
                // Delete old file
                if ($expense->receipt_file) {
                    Storage::disk('public')->delete($expense->receipt_file);
                }
                $data['receipt_file'] = $request->file('receipt_file')->store('receipts', 'public');
            }

            $expense->update($data);

            return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating expense: ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            // Delete file if exists
            if ($expense->receipt_file) {
                Storage::disk('public')->delete($expense->receipt_file);
            }

            $expense->delete();
            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting expense: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, Expense $expense)
    {
        // $request->validate([
        //     'status' => 'required|in:approved,rejected',
        // ]);
        //dd('ok');
        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        $message = $request->status === 'approved' ? 'Expense approved successfully!' : 'Expense rejected successfully!';
        return back()->with('success', $message);
    }
}
