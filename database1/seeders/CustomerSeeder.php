<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'রহিম উদ্দিন',
                'phone' => '01711111111',
                'email' => 'rahim@example.com',
                'address' => 'ঢাকা, বাংলাদেশ',
            ],
            [
                'name' => 'করিম আহমেদ',
                'phone' => '01722222222',
                'email' => 'karim@example.com',
                'address' => 'চট্টগ্রাম, বাংলাদেশ',
            ],
            [
                'name' => 'ফাতেমা খাতুন',
                'phone' => '01733333333',
                'email' => 'fatema@example.com',
                'address' => 'সিলেট, বাংলাদেশ',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
