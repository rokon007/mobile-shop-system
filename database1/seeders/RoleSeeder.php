<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $salesman = Role::create(['name' => 'Salesman']);
        $accountant = Role::create(['name' => 'Accountant']);
        $inventoryManager = Role::create(['name' => 'Inventory Manager']);

        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());

        // Manager permissions
        $managerPermissions = [
            'user-view', 'product-management', 'purchase-management', 'sales-management',
            'customer-management', 'employee-management', 'attendance-management',
            'leave-management', 'leave-approve', 'finance-management', 'expense-approve',
            'loan-management', 'loan-approve', 'inventory-management', 'reports-view'
        ];
        $manager->givePermissionTo($managerPermissions);

        // Salesman permissions
        $salesmanPermissions = [
            'product-view', 'sales-management', 'pos-access', 'customer-management',
            'inventory-view'
        ];
        $salesman->givePermissionTo($salesmanPermissions);

        // Accountant permissions
        $accountantPermissions = [
            'purchase-view', 'sales-view', 'customer-view', 'supplier-view',
            'finance-management', 'expense-approve', 'loan-management',
            'reports-view', 'export-reports'
        ];
        $accountant->givePermissionTo($accountantPermissions);

        // Inventory Manager permissions
        $inventoryManagerPermissions = [
            'product-management', 'purchase-management', 'supplier-management',
            'inventory-management', 'warehouse-management', 'stock-transfer',
            'damaged-products', 'inventory-report'
        ];
        $inventoryManager->givePermissionTo($inventoryManagerPermissions);
    }
}
