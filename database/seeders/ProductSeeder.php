<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $samsung = Brand::firstOrCreate(['slug' => 'samsung'], ['name' => 'Samsung']);
        $apple   = Brand::firstOrCreate(['slug' => 'apple'], ['name' => 'Apple']);
        $xiaomi  = Brand::firstOrCreate(['slug' => 'xiaomi'], ['name' => 'Xiaomi']);

        $brands = [$samsung, $apple, $xiaomi];

        $smartphone = Category::firstOrCreate(['slug' => 'smartphone'], ['name' => 'Smartphone']);
        $accessory  = Category::firstOrCreate(['slug' => 'accessory'], ['name' => 'Accessory']);
        $categories = [$smartphone, $accessory];

        // বাস্তবসম্মত প্রোডাক্ট নামের লিস্ট
        $modelNames = [
            'Samsung Galaxy S22',
            'Samsung Galaxy A54',
            'Samsung Galaxy M14',
            'iPhone 13',
            'iPhone 14 Pro',
            'iPhone SE (2022)',
            'Xiaomi Redmi Note 12',
            'Xiaomi 13T Pro',
            'Xiaomi Poco X5',
        ];

        $productTypes = ['mobile', 'accessory', 'other'];

        for ($i = 1; $i <= 50; $i++) {
            $name = $faker->randomElement($modelNames);
            $slug = Str::slug($name . '-' . $i);
            $sku  = strtoupper(Str::random(8));
            $barcode = $faker->unique()->ean13();
            $imei = $faker->unique()->numerify('35###########'); // 15 digit IMEI
            $serialNumber = strtoupper(Str::random(12));

            $brand = $faker->randomElement($brands);
            $category = $faker->randomElement($categories);
            $productType = $faker->randomElement($productTypes);

            Product::create([
                'name' => $name,
                'slug' => $slug,
                'sku' => $sku,
                'barcode' => $barcode,
                'imei' => $imei,
                'serial_number' => $serialNumber,
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'model' => strtoupper(Str::random(6)),
                'description' => $faker->paragraph(),
                'purchase_price' => $faker->randomFloat(2, 8000, 90000),
                'selling_price' => $faker->randomFloat(2, 10000, 120000),
                'wholesale_price' => $faker->randomFloat(2, 9000, 115000),
                'stock_quantity' => $faker->numberBetween(1, 50),
                'min_stock_alert' => $faker->numberBetween(1, 10),
                'images' => json_encode([
                    $faker->imageUrl(300, 300, 'technics', true),
                    $faker->imageUrl(300, 300, 'technics', true),
                ]),
                'warranty_months' => $faker->randomElement([0, 6, 12, 24]),
                'warranty_terms' => $faker->sentence(),
                'product_type' => $productType,
                'specifications' => json_encode([
                    'RAM' => $faker->randomElement(['4GB', '6GB', '8GB']),
                    'Storage' => $faker->randomElement(['64GB', '128GB', '256GB']),
                    'Battery' => $faker->randomElement(['4000mAh', '5000mAh']),
                    'Display' => $faker->randomElement(['6.1"', '6.5"', '6.7"']),
                ]),
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}
