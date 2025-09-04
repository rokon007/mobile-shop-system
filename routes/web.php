<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Filter\CategoryFilterComponent;
use App\Livewire\Filter\InventoryManagementComponent;
use App\Http\Controllers\InvoiceController;
use App\Livewire\EditPhonePurchaseForm;
use App\Http\Controllers\PurchaseInvoiceController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/storage-link', function () {
    // Artisan command call
    try {
        Artisan::call('storage:link');
        $output = Artisan::output();

        return response()->json([
            'success' => true,
            'message' => 'Storage link created successfully.',
            'output' => $output,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error occurred: ' . $e->getMessage(),
        ], 500);
    }
})->name('storage.link')->middleware('auth');

// Include Breeze authentication routes
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // User Management
    Route::middleware(['permission:user-management'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    // Product Management
    Route::middleware(['permission:product-management'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('/category-filter/{slug}', CategoryFilterComponent::class)->name('category_filter');
        Route::get('/products/inventory/{id}', InventoryManagementComponent::class)->name('product_inventory');
    });

    // Purchase Management
    Route::middleware(['permission:purchase-management'])->group(function () {
        Route::resource('purchases', PurchaseController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('/purchases/{purchase}/invoice', [PurchaseController::class, 'invoice'])->name('purchases.invoice');
        Route::post('/purchases/{purchase}/payment', [PurchaseController::class, 'payment'])->name('purchases.payment');

        Route::view('/purchase/new', 'pages.purchase-new')->name('purchase.new');
        Route::view('/purchase/search', 'pages.purchase-search')->name('purchase.search');

        Route::get('/invoice/{imei}', [InvoiceController::class, 'generate'])
            ->where('imei', '[A-Za-z0-9\-]+')
            ->name('invoice.generate');
        Route::get('/purchases/{id}/edit', [InvoiceController::class, 'edit'])->name('purchase.edit');
        Route::get('/purchases/{id}/invoice', [InvoiceController::class, 'invoice'])->name('purchase.invoice');
        Route::get('/purchases/used/{id}/invoice', [PurchaseInvoiceController::class, 'pinvoice'])->name('purchase.pinvoice');
        Route::get('/phone-purchase/{purchase_phone_id}/edit', EditPhonePurchaseForm::class)
    ->name('phone.purchase.edit');
    });

    // Sales Management
    Route::middleware(['permission:sales-management'])->group(function () {
        Route::get('/pos', [SaleController::class, 'pos'])->name('pos.index');
        Route::resource('sales', SaleController::class);
        Route::resource('customers', CustomerController::class);
        Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
        Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
        Route::post('/sales/{sale}/payment', [SaleController::class, 'payment'])->name('sales.payment');
    });

    // Employee Management
    Route::middleware(['permission:employee-management'])->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('attendance', AttendanceController::class);
        Route::post('/attendances/{attendance}/check-out', [AttendanceController::class, 'checkOut'])->name('attendances.check-out');
        //Route::resource('leaves', LeaveController::class);
        //Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
       // Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');



        // HR Management
        Route::resource('attendances', AttendanceController::class);
        Route::resource('leaves', LeaveController::class);
        Route::patch('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::patch('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    });



    // Finance & Accounting
    Route::middleware(['permission:finance-management'])->group(function () {
        Route::resource('expenses', ExpenseController::class);
        Route::resource('loans', LoanController::class);
        Route::post('/loans/{loan}/payment', [LoanController::class, 'payment'])->name('loans.payment');
        Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    });

    // Inventory Management
    Route::middleware(['permission:inventory-management'])->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('stock-transfers', StockTransferController::class);
        Route::post('/stock-transfers/{stockTransfer}/receive', [StockTransferController::class, 'receive'])->name('stock-transfers.receive');
        Route::get('/inventory/damaged', [InventoryController::class, 'damaged'])->name('inventory.damaged');
        Route::post('/inventory/damaged', [InventoryController::class, 'storeDamaged'])->name('inventory.damaged.store');
    });

    // Reports & Analytics
    Route::middleware(['permission:reports-view'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/products', [ReportController::class, 'product'])->name('reports.product');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/customer', [ReportController::class, 'customer'])->name('reports.customer');
        Route::get('/reports/employee', [ReportController::class, 'employee'])->name('reports.employee');
        Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // System Settings
    Route::middleware(['permission:system-settings'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        //Route::post('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
        Route::match(['get', 'post'], '/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
        Route::get('/backup/download/{filename}', [SettingController::class, 'downloadBackup'])->name('settings.backup.download')->where('filename', '.*');
        Route::delete('/settings/backup/delete/{file}', [SettingController::class, 'deleteBackup'])->name('settings.backup.delete');
    });
});
