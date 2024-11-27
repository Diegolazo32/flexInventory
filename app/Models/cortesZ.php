<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cortesZ extends Model
{
    use HasFactory;

    protected $fillable = [
        'ventas',
        'devoluciones',
        'IVA',
        'salidas',
        'entradas',
        'total'
    ];
}
