<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proveedores extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefonoPrincipal',
        'emailPrincipal',
        'NIT',
        'representante',
        'telefonoRepresentante',
        'emailRepresentante',
        'estado',
    ];
}
