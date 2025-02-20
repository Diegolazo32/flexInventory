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
        Schema::create('venta_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta')->references('id')->on('ventas');
            //$table->foreignId('producto')->references('id')->on('productos');
            $table->integer('cantidad');
            $table->decimal('precio');
            $table->decimal('descuento');
            $table->decimal('descuentoUsuario');
            $table->decimal('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_productos');
    }
};
