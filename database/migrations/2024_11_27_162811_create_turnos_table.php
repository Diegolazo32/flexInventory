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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor')->references('id')->on('users');
            $table->foreignId('caja')->references('id')->on('cajas');
            $table->dateTime('apertura');
            $table->decimal('montoInicial');
            $table->decimal('totalVentas')->nullable();
            $table->decimal('totalEntradas')->nullable();
            $table->decimal('totalSalidas')->nullable();
            $table->dateTime('cierre')->nullable();
            $table->decimal('montoCierre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
