<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_no',
        'loan_type',
        'employee_id',
        'customer_id',
        'loan_amount',
        'interest_rate',
        'duration_months',
        'monthly_installment',
        'paid_amount',
        'remaining_amount',
        'loan_date',
        'due_date',
        'status',
        'purpose',
        'terms_conditions',
        'approved_by',
        'created_by'
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
