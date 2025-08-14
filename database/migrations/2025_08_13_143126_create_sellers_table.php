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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('nid_number')->unique();
            $table->text('permanent_address')->nullable();
            $table->text('present_address')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('nid_photo_path')->nullable();
            $table->string('purchase_receipt_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
