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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->json('attribute_combination'); // Attributes এর কম্বিনেশন (JSON)
            $table->integer('quantity')->default(0);
            $table->double('purchase_price', 8, 2);
            $table->double('selling_price', 8, 2)->nullable();
            $table->string('sku', 255)->unique();
            $table->string('imei')->nullable()->unique();
            $table->string('serial_number')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
