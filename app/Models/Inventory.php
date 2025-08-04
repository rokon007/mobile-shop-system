<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'attribute_combination', 'quantity', 'purchase_price','selling_price','sku','imei','serial_number'];

    public function getAttributeCombinationAttribute($value)
    {
        return json_decode($value, true); // JSON ডেটা অ্যারে হিসেবে ফেরত দেবে
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function productImage()
    // {
    //     return $this->belongsTo(ProductImage::class);
    // }


    // public function images()
    // {
    //     return $this->hasMany(ProductImage::class, 'inventory_id');
    // }

    // Decode the attribute combination JSON
    // public function getAttributeCombinationAttribute($value)
    // {
    //     return json_decode($value, true);
    // }

    // Encode the attribute combination JSON
    public function setAttributeCombinationAttribute($value)
    {
        $this->attributes['attribute_combination'] = json_encode($value);
    }

}
