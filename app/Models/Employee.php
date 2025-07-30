<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'designation',
        'department',
        'basic_salary',
        'house_rent',
        'medical_allowance',
        'transport_allowance',
        'other_allowance',
        'joining_date',
        'leaving_date',
        'photo',
        'documents',
        'emergency_contact',
        'employment_type',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'leaving_date' => 'date',
        'documents' => 'array',
        'basic_salary' => 'decimal:2',
        'house_rent' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getTotalSalaryAttribute()
    {
        return $this->basic_salary + $this->house_rent + $this->medical_allowance + 
               $this->transport_allowance + $this->other_allowance;
    }
}
