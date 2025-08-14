<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseFhone extends Model
{
     use HasFactory;

    protected $fillable = [
        'seller_id','phone_id','purchase_price','purchase_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function seller() { return $this->belongsTo(Seller::class); }
    public function phone() { return $this->belongsTo(Phone::class); }
}
