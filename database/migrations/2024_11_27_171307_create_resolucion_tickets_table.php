<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*Una resolución es un documento que autoriza a un contribuyente a emitir facturas,
    notas de crédito, notas de débito, recibos y otros documentos tributarios.

    La resolución contiene información como:
    - Número de resolución
    - Serie de documentos
    - Rango de documentos
    - Fecha de emisión
    - Número de autorización
    - Estado de la resolución


    Se llena con estos datos por ejemplo:
    - Resolución: 001-001-01-000000001
    - Serie: A
    - Rango de documentos: 000000001 al 000000100
    - Fecha de emisión: 01/01/2024
    - Número de autorización: 001-001-01-000000001
    - Estado de la resolución: Activa
    */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resolucion_tickets', function (Blueprint $table) {

            $table->id();
            $table->string('resolucion');
            $table->string('serie');
            $table->string('desde');
            $table->string('hasta');
            $table->date('fecha');
            $table->string('autorizacion');
            $table->foreignId('estado')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resolucion_tickets');
    }
};
