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
        /*Schema::create('venta_tickets', function (Blueprint $table) {
            $table->id();
            $table->float('numero');
            $table->foreignId('venta')->references('id')->on('ventas');
            $table->foreignId('resolucion')->references('id')->on('resolucion_tickets');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_tickets');
    }
};
