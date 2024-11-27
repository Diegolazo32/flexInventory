<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ventaProductos extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta',
        'producto',
        'cantidad',
        'precio',
        'descuento',
        'descuentoUsuario',
    ];
}
