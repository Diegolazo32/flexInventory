<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'estado'
    ];
}
