<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('logo')->default('logo/empresa_logo.jpg')->nullable();
            //$table->string('horario');
            $table->string('NIT');
            $table->string('NRC');
            $table->string('giro');
            $table->string('representante');
            $table->string('dui');
            $table->string('nit_representante');
            $table->string('telefono_representante');
            $table->string('email_representante');
            $table->string('cuentaContable');
            $table->decimal('valorIVA');
            $table->boolean('firstTime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
