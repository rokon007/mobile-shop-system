<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
        'received_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /**
     * সেলের সাথে সম্পর্ক
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * যে ইউজার পেমেন্ট গ্রহণ করেছেন তার সাথে সম্পর্ক
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * পেমেন্ট মেথডের লেবেল পাওয়ার জন্য
     */
    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'cash' => 'Cash',
            'card' => 'Card',
            'bikash' => 'Bkash',
            'nagad' => 'Nagad',
            'roket' => 'Roket'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }
}
