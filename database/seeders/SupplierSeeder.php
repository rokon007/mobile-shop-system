<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'আহমেদ ট্রেডিং',
                'company_name' => 'Ahmed Trading Corporation',
                'phone' => '01711111111',
                'email' => 'ahmed@trading.com',
                'address' => 'ঢাকা, বাংলাদেশ',
                'opening_balance' => 0,
                'current_balance' => 0,
            ],
            [
                'name' => 'করিম এন্টারপ্রাইজ',
                'company_name' => 'Karim Enterprise Ltd',
                'phone' => '01722222222',
                'email' => 'karim@enterprise.com',
                'address' => 'চট্টগ্রাম, বাংলাদেশ',
                'opening_balance' => 0,
                'current_balance' => 0,
            ],
            [
                'name' => 'রহিম ইমপোর্ট',
                'company_name' => 'Rahim Import & Export',
                'phone' => '01733333333',
                'email' => 'rahim@import.com',
                'address' => 'সিলেট, বাংলাদেশ',
                'opening_balance' => 0,
                'current_balance' => 0,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
