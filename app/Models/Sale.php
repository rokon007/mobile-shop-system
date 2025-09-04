<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'customer_name',
        'customer_phone',
        'sale_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'note',
        'sale_type',
        'is_emi',
        'emi_months',
        'emi_amount',
        'created_by'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'emi_amount' => 'decimal:2',
        'is_emi' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getProfitAttribute()
    {
        $totalCost = $this->items->sum(function ($item) {
            return $item->product->purchase_price * $item->quantity;
        });
        return $this->total_amount - $totalCost;
    }

    /**
     * পেমেন্টগুলোর সাথে সম্পর্ক
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
