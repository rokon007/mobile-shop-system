<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand','model','manufacture_year','imei','serial_number','ram','rom'
    ];

    public function purchase()
    {
        return $this->hasOne(PurchaseFhone::class);
    }
}
