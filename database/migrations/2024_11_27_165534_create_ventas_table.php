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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->foreignId('cliente')->references('id')->on('clientes');
            $table->foreignId('vendedor')->references('id')->on('users');
            $table->foreignId('turno')->references('id')->on('turnos');
            $table->float('total');
            //$table->foreignId('resolucion')->references('id')->on('resolucion_tickets');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
