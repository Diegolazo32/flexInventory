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
        Schema::table('venta_productos', function (Blueprint $table) {
            $table->foreignId('producto')->references('id')->on('productos')->after('venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta_productos', function (Blueprint $table) {
            $table->dropForeign(['producto']);
        });
    }
};
