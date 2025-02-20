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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->decimal('precioCompra');
            $table->decimal('precioVenta');
            $table->decimal('precioDescuento')->nullable();
            $table->decimal('precioEspecial')->nullable();
            $table->date('fechaVencimiento')->nullable();
            $table->integer('stock');
            $table->integer('stockInicial');
            $table->integer('stockMinimo')->nullable();
            $table->integer('stockMaximo')->nullable();
            $table->foreignId('categoria')->references('id')->on('categorias');
            $table->foreignId('tipoVenta')->references('id')->on('tipo_ventas');
            $table->foreignId('proveedor')->references('id')->on('proveedores');
            //$table->foreignId('unidad')->references('id')->on('unidades');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
