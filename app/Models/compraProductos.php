<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class compraProductos extends Model
{
    use HasFactory;

    protected $fillable = [
        'compra',
        'producto',
        'proveedor',
        'cantidad',
        'precio',
        'total',
    ];
}
