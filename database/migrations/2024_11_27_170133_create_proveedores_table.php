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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefonoPrincipal');
            $table->string('emailPrincipal');
            $table->string('NIT');
            //$table->string('NRC'); //Número de Registro de Contribuyente
            $table->string('representante');
            $table->string('telefonoRepresentante');
            $table->string('emailRepresentante');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
