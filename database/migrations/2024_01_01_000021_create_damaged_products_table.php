<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('damaged_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->enum('damage_type', ['physical', 'water', 'software', 'expired', 'other']);
            $table->text('description');
            $table->decimal('loss_amount', 12, 2);
            $table->date('damage_date');
            $table->string('image')->nullable();
            $table->enum('action_taken', ['repair', 'return_supplier', 'dispose', 'sell_discount'])->nullable();
            $table->foreignId('reported_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('damaged_products');
    }
};
