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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->decimal('numero');
            $table->decimal('cantidad');
            $table->date('fechaVencimiento')->nullable();
            $table->foreignId('producto')->references('id')->on('productos');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->foreignId('inventario')->references('id')->on('inventarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
