<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('imei')->nullable()->unique();
            $table->string('serial_number')->nullable()->unique();
            $table->enum('status', ['available', 'sold', 'damaged', 'returned'])->default('available');
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_serials');
    }
};
