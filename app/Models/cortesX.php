<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cortesX extends Model
{
    use HasFactory;

    protected $fillable = [
        'turno',
        'ventas',
        'devoluciones',
        'IVA',
        'salidas',
        'entradas',
        'total',
        'diferencia',
        'estado',
        'corteZ'
    ];
}
