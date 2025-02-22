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
        Schema::create('compra_productos', function (Blueprint $table) {
            $table->id();
            //$table->integer('codigo');
            $table->foreignId('producto')->references('id')->on('productos');
            $table->integer('cantidad');
            $table->foreignId('proveedor')->references('id')->on('proveedores');
            $table->decimal('precioCompra');
            $table->decimal('totalCompra');
            $table->foreignId('inventario')->references('id')->on('inventarios');
            $table->foreignId('compra')->references('id')->on('compras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compra_productos');
    }
};
