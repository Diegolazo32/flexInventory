<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ventaTickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'venta',
        'resolucion',
        'estado',
    ];
}
