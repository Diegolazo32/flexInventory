<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'numero',
        'cantidad',
        'fechaVencimiento',
        'producto',
        'estado',
        'inventario',
    ];
}
