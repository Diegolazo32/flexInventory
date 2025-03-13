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
        Schema::create('movimientos_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno')->references('id')->on('turnos');
            $table->integer('tipo');
            $table->decimal('valor');
            $table->string('descripcion');
            $table->foreignId('usuario')->references('id')->on('users');
            $table->foreignId('caja')->references('id')->on('cajas');
            $table->foreignId('inventario')->references('id')->on('inventarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_cajas');
    }
};
