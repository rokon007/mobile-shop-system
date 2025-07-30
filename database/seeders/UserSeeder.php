<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@mobileshop.com',
            'password' => Hash::make('password'),
            'phone' => '01712345678',
            'address' => 'Dhaka, Bangladesh',
            'status' => 'active',
        ]);
        $admin->assignRole('Admin');

        // Create Manager User
        $manager = User::create([
            'name' => 'Shop Manager',
            'email' => 'manager@mobileshop.com',
            'password' => Hash::make('password'),
            'phone' => '01712345679',
            'address' => 'Dhaka, Bangladesh',
            'status' => 'active',
        ]);
        $manager->assignRole('Manager');

        // Create Salesman User
        $salesman = User::create([
            'name' => 'Sales Person',
            'email' => 'sales@mobileshop.com',
            'password' => Hash::make('password'),
            'phone' => '01712345680',
            'address' => 'Dhaka, Bangladesh',
            'status' => 'active',
        ]);
        $salesman->assignRole('Salesman');

        // Create Accountant User
        $accountant = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@mobileshop.com',
            'password' => Hash::make('password'),
            'phone' => '01712345681',
            'address' => 'Dhaka, Bangladesh',
            'status' => 'active',
        ]);
        $accountant->assignRole('Accountant');

        // Create Inventory Manager User
        $inventoryManager = User::create([
            'name' => 'Inventory Manager',
            'email' => 'inventory@mobileshop.com',
            'password' => Hash::make('password'),
            'phone' => '01712345682',
            'address' => 'Dhaka, Bangladesh',
            'status' => 'active',
        ]);
        $inventoryManager->assignRole('Inventory Manager');
    }
}
