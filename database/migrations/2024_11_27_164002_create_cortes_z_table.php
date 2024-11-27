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
        Schema::create('cortes_z', function (Blueprint $table) {
            $table->id();
            $table->float('ventas');
            $table->float('devoluciones');
            $table->float('IVA');
            $table->float('salidas');
            $table->float('entradas');
            $table->float('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cortes_z_s');
    }
};
