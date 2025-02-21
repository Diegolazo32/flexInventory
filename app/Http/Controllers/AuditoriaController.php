<?php

namespace App\Http\Controllers;

use App\Models\auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function registrarEvento($usuario, $evento, $tabla, $registroAnterior, $registroNuevo)
    {
        $auditoria = new auditoria();
        $auditoria->usuario = $usuario;
        $auditoria->evento = $evento;
        $auditoria->tabla = $tabla;
        $auditoria->registroAnterior = $registroAnterior;
        $auditoria->registroNuevo = $registroNuevo;
        $auditoria->save();
    }
}
