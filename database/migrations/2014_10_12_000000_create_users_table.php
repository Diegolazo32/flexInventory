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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('DUI')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->integer('edad')->nullable();
            $table->integer('genero');
            //$table->foreignId('rol')->references('id')->on('roles');
            //$table->foreignId('estado')->references('id')->on('estados');
            $table->string('usuario')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
