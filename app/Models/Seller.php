<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
   use HasFactory;

    protected $fillable = [
        'name','father_name','mother_name','nid_number','permanent_address','present_address','dob',
        'phone','email','facebook_id','photo_path','nid_photo_path','purchase_receipt_path'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function purchases()
    {
        return $this->hasMany(PurchaseFhone::class);
    }
}
