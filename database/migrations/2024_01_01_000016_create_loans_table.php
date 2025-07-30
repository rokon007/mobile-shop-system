<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_no')->unique();
            $table->enum('loan_type', ['employee', 'customer', 'business']);
            $table->foreignId('employee_id')->nullable()->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('duration_months');
            $table->decimal('monthly_installment', 12, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('loan_date');
            $table->date('due_date');
            $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');
            $table->text('purpose')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->foreignId('approved_by')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
