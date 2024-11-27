<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class movimientosCaja extends Model
{
    use HasFactory;

    protected $fillable = [
        'turno',
        'tipo',
        'valor',
        'descripcion',
    ];
}
