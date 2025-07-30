<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'employee_id' => 'EMP001',
                'name' => 'মোহাম্মদ আলী',
                'phone' => '01711111111',
                'email' => 'ali@mobileshop.com',
                'address' => 'ঢাকা, বাংলাদেশ',
                'designation' => 'Sales Manager',
                'position' => 'Sales Manager',
                'department' => 'Sales',
                'salary' => 25000,
                'join_date' => '2023-01-01',
            ],
            [
                'employee_id' => 'EMP002',
                'name' => 'ফাতেমা খাতুন',
                'phone' => '01722222222',
                'email' => 'fatema@mobileshop.com',
                'address' => 'চট্টগ্রাম, বাংলাদেশ',
                'designation' => 'Accountant',
                'position' => 'Accountant',
                'department' => 'Accounts',
                'salary' => 22000,
                'join_date' => '2023-01-01',
            ],
            [
                'employee_id' => 'EMP003',
                'name' => 'রাহুল আহমেদ',
                'phone' => '01733333333',
                'email' => 'rahul@mobileshop.com',
                'address' => 'সিলেট, বাংলাদেশ',
                'designation' => 'Sales Executive',
                'position' => 'Sales Executive',
                'department' => 'Sales',
                'salary' => 18000,
                'join_date' => '2023-01-01',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
