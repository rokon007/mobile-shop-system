<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'brand_id',
        'category_id',
        'model',
        'description',
        'purchase_price',
        'selling_price',
        'wholesale_price',
        'stock_quantity',
        'min_stock_alert',
        'images',
        'warranty_months',
        'warranty_terms',
        'product_type',
        'specifications',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function attributes()
    // {
    //     return $this->hasMany(ProductAttribute::class);
    // }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function serials()
    {
        return $this->hasMany(ProductSerial::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function damagedProducts()
    {
        return $this->hasMany(DamagedProduct::class);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= min_stock_alert');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getProfitMarginAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getProfitPercentageAttribute()
    {
        if ($this->purchase_price > 0) {
            return (($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100;
        }
        return 0;
    }
}
