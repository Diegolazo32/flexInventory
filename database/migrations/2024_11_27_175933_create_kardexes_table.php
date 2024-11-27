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
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('producto')->references('id')->on('productos');
            $table->float('cantidad');
            $table->integer('tipo');
            //stock inicial
            $table->float('stockInicial');
            $table->foreignId('inventario')->references('id')->on('inventarios');
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
