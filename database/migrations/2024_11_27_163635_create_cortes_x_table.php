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
        Schema::create('cortes_x', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno')->references('id')->on('turnos');
            $table->float('ventas', 8, 2);
            $table->float('devoluciones');
            $table->float('IVA');
            $table->float('salidas');
            $table->float('entradas');
            $table->float('total');
            $table->float('diferencia');
            $table->foreignId('estado')->references('id')->on('estados');
            //$table->foreignId('corteZ')->references('id')->on('cortes_z');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cortes_x_e_s');
    }
};
