<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['employee', 'customer', 'creator', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();
        
        return view('loans.create', compact('employees', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_type' => 'required|in:employee,customer,business',
            'employee_id' => 'required_if:loan_type,employee|exists:employees,id',
            'customer_id' => 'required_if:loan_type,customer|exists:customers,id',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'purpose' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate loan number
            $lastLoan = Loan::latest()->first();
            $loanNo = 'LOAN-' . date('Y') . '-' . str_pad(($lastLoan ? $lastLoan->id + 1 : 1), 6, '0', STR_PAD_LEFT);

            // Calculate monthly installment
            $principal = $request->loan_amount;
            $rate = $request->interest_rate / 100 / 12; // Monthly interest rate
            $months = $request->duration_months;
            
            if ($rate > 0) {
                $monthlyInstallment = $principal * ($rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
            } else {
                $monthlyInstallment = $principal / $months;
            }

            // Calculate due date
            $dueDate = date('Y-m-d', strtotime($request->loan_date . ' + ' . $months . ' months'));

            $loan = Loan::create([
                'loan_no' => $loanNo,
                'loan_type' => $request->loan_type,
                'employee_id' => $request->employee_id,
                'customer_id' => $request->customer_id,
                'loan_amount' => $principal,
                'interest_rate' => $request->interest_rate,
                'duration_months' => $months,
                'monthly_installment' => round($monthlyInstallment, 2),
                'remaining_amount' => $principal,
                'loan_date' => $request->loan_date,
                'due_date' => $dueDate,
                'purpose' => $request->purpose,
                'terms_conditions' => $request->terms_conditions,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'created_by' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('loans.index')->with('success', 'Loan created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating loan: ' . $e->getMessage());
        }
    }

    public function show(Loan $loan)
    {
        $loan->load(['employee', 'customer', 'creator', 'approver', 'payments']);
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $employees = Employee::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get();
        
        return view('loans.edit', compact('loan', 'employees', 'customers'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'loan_type' => 'required|in:employee,customer,business',
            'employee_id' => 'required_if:loan_type,employee|exists:employees,id',
            'customer_id' => 'required_if:loan_type,customer|exists:customers,id',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1',
            'loan_date' => 'required|date',
            'purpose' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);

        try {
            // Calculate monthly installment
            $principal = $request->loan_amount;
            $rate = $request->interest_rate / 100 / 12;
            $months = $request->duration_months;
            
            if ($rate > 0) {
                $monthlyInstallment = $principal * ($rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
            } else {
                $monthlyInstallment = $principal / $months;
            }

            $dueDate = date('Y-m-d', strtotime($request->loan_date . ' + ' . $months . ' months'));

            $loan->update([
                'loan_type' => $request->loan_type,
                'employee_id' => $request->employee_id,
                'customer_id' => $request->customer_id,
                'loan_amount' => $principal,
                'interest_rate' => $request->interest_rate,
                'duration_months' => $months,
                'monthly_installment' => round($monthlyInstallment, 2),
                'loan_date' => $request->loan_date,
                'due_date' => $dueDate,
                'purpose' => $request->purpose,
                'terms_conditions' => $request->terms_conditions,
            ]);

            return redirect()->route('loans.index')->with('success', 'Loan updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating loan: ' . $e->getMessage());
        }
    }

    public function destroy(Loan $loan)
    {
        try {
            $loan->payments()->delete();
            $loan->delete();
            return redirect()->route('loans.index')->with('success', 'Loan deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting loan: ' . $e->getMessage());
        }
    }

    public function payment(Request $request, Loan $loan)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0|max:' . $loan->remaining_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create payment record
            LoanPayment::create([
                'loan_id' => $loan->id,
                'payment_amount' => $request->payment_amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);

            // Update loan
            $newPaidAmount = $loan->paid_amount + $request->payment_amount;
            $newRemainingAmount = $loan->loan_amount - $newPaidAmount;
            
            $status = $newRemainingAmount <= 0 ? 'completed' : 'active';

            $loan->update([
                'paid_amount' => $newPaidAmount,
                'remaining_amount' => max(0, $newRemainingAmount),
                'status' => $status,
            ]);

            DB::commit();
            return back()->with('success', 'Payment recorded successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }
}
