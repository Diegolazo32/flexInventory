<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class turnos extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendedor',
        'caja',
        'apertura',
        'montoInicial',
        'totalVentas',
        'totalEntradas',
        'totalSalidas',
        'cierre',
        'montoCierre',
    ];
}
