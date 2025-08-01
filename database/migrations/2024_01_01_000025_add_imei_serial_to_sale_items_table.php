<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->json('imei_numbers')->nullable()->after('warranty_info');
            $table->json('serial_numbers')->nullable()->after('imei_numbers');
        });
    }

    public function down()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['imei_numbers', 'serial_numbers']);
        });
    }
};
