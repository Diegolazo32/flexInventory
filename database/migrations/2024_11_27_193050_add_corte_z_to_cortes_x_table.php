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
        Schema::table('cortes_x', function (Blueprint $table) {
            $table->foreignId('corte_z')->references('id')->on('cortes_z')->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cortes_x', function (Blueprint $table) {
            $table->dropForeign(['corte_z']);
        });
    }
};
