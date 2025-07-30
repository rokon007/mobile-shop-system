<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Smartphone', 'slug' => 'smartphone'],
            ['name' => 'Feature Phone', 'slug' => 'feature-phone'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Cases & Covers', 'slug' => 'cases-covers'],
            ['name' => 'Chargers', 'slug' => 'chargers'],
            ['name' => 'Headphones', 'slug' => 'headphones'],
            ['name' => 'Memory Cards', 'slug' => 'memory-cards'],
            ['name' => 'Power Banks', 'slug' => 'power-banks'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
