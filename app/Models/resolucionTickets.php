<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resolucionTickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'resolucion',
        'serie',
        'desde',
        'hasta',
        'fecha',
        'estado',
        'autorizacion',
        'estado',
    ];
}
