<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class auditoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario',
        'evento',
        'tabla',
        'registroAnterior',
        'registroNuevo'
    ];
}
