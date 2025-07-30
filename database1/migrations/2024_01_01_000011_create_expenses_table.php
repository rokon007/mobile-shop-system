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
            $table->enum('type', ['salary', 'utility', 'rent', 'daily', 'monthly', 'yearly', 'other']);
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->string('receipt')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
