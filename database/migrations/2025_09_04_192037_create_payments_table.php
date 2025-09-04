<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'bikash', 'nagad','roket']);
            $table->dateTime('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // ইন্ডেক্স যোগ করা
            $table->index('sale_id');
            $table->index('payment_date');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
