<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Huawei', 'slug' => 'huawei'],
            ['name' => 'OnePlus', 'slug' => 'oneplus'],
            ['name' => 'Oppo', 'slug' => 'oppo'],
            ['name' => 'Vivo', 'slug' => 'vivo'],
            ['name' => 'Realme', 'slug' => 'realme'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
