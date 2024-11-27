<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precioCompra',
        'precioVenta',
        'precioDescuento',
        'precioEspecial',
        'fechaVencimiento',
        'stock',
        'stockInicial',
        'stockMinimo',
        'stockMaximo',
        'categoria',
        'tipoVenta',
        'proveedor',
        'estado',
        'unidad',
    ];
}
