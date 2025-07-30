<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', [
                'salary_payment', 
                'utility_expense', 
                'shop_rent', 
                'daily_expense', 
                'monthly_expense', 
                'yearly_expense',
                'marketing',
                'maintenance',
                'transport',
                'other'
            ]);
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'mobile_banking'])->default('cash');
            $table->string('reference_no')->nullable();
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
