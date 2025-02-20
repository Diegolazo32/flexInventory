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
        Schema::create('kardexes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto')->references('id')->on('productos');
            $table->decimal('cantidad');
            $table->integer('accion'); //1 entrada 2 salida
            //$table->decimal('stockInicial');
            $table->foreignId('inventario')->references('id')->on('inventarios');
            //$table->foreignId('lote')->references('id')->on('lotes');
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardexes');
    }
};
