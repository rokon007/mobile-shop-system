<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'opening_balance',
        'current_balance',
        'credit_limit',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getTotalPurchaseAttribute()
    {
        return $this->sales()->sum('total_amount');
    }

    public function getTotalDueAttribute()
    {
        return $this->sales()->sum('due_amount');
    }
}
