<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            'user-management',
            'user-create',
            'user-edit',
            'user-delete',
            'user-view',
            
            // Role Management
            'role-management',
            'role-create',
            'role-edit',
            'role-delete',
            'role-view',
            
            // Product Management
            'product-management',
            'product-create',
            'product-edit',
            'product-delete',
            'product-view',
            
            // Brand Management
            'brand-management',
            'brand-create',
            'brand-edit',
            'brand-delete',
            'brand-view',
            
            // Category Management
            'category-management',
            'category-create',
            'category-edit',
            'category-delete',
            'category-view',
            
            // Purchase Management
            'purchase-management',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'purchase-view',
            
            // Supplier Management
            'supplier-management',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'supplier-view',
            
            // Sales Management
            'sales-management',
            'sales-create',
            'sales-edit',
            'sales-delete',
            'sales-view',
            'pos-access',
            
            // Customer Management
            'customer-management',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'customer-view',
            
            // Employee Management
            'employee-management',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'employee-view',
            
            // Attendance Management
            'attendance-management',
            'attendance-create',
            'attendance-edit',
            'attendance-delete',
            'attendance-view',
            
            // Leave Management
            'leave-management',
            'leave-create',
            'leave-edit',
            'leave-delete',
            'leave-view',
            'leave-approve',
            
            // Finance Management
            'finance-management',
            'expense-create',
            'expense-edit',
            'expense-delete',
            'expense-view',
            'expense-approve',
            
            // Loan Management
            'loan-management',
            'loan-create',
            'loan-edit',
            'loan-delete',
            'loan-view',
            'loan-approve',
            
            // Inventory Management
            'inventory-management',
            'inventory-view',
            'warehouse-management',
            'stock-transfer',
            'damaged-products',
            
            // Reports & Analytics
            'reports-view',
            'sales-report',
            'profit-loss-report',
            'inventory-report',
            'customer-report',
            'employee-report',
            'export-reports',
            
            // System Settings
            'system-settings',
            'backup-database',
            'sms-settings',
            'email-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
