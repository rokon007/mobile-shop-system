<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $samsungBrand = Brand::where('slug', 'samsung')->first();
        $appleBrand = Brand::where('slug', 'apple')->first();
        $smartphoneCategory = Category::where('slug', 'smartphone')->first();

        $products = [
            [
                'name' => 'Samsung Galaxy S23',
                'slug' => 'samsung-galaxy-s23',
                'brand_id' => $samsungBrand->id,
                'category_id' => $smartphoneCategory->id,
                'model' => 'SM-S911B',
                'description' => 'Latest Samsung flagship smartphone',
                'purchase_price' => 85000,
                'selling_price' => 95000,
                'stock_quantity' => 10,
                'min_stock_alert' => 3,
                'warranty_months' => 12,
            ],
            [
                'name' => 'iPhone 14',
                'slug' => 'iphone-14',
                'brand_id' => $appleBrand->id,
                'category_id' => $smartphoneCategory->id,
                'model' => 'A2882',
                'description' => 'Apple iPhone 14 with A15 Bionic chip',
                'purchase_price' => 120000,
                'selling_price' => 135000,
                'stock_quantity' => 5,
                'min_stock_alert' => 2,
                'warranty_months' => 12,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
