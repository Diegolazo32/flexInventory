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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->date('fechaApertura');
            $table->date('fechaCierre')->nullable();
            $table->integer('ProductosApertura');
            $table->integer('StockApertura');
            $table->integer('ProductosCierre');
            $table->integer('StockCierre');
            $table->float('totalInventario');
            $table->foreignId('aperturadoPor')->references('id')->on('users');
            $table->foreignId('cerradoPor')->references('id')->on('users');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
