<?php

namespace App\Http\Controllers;

use App\Models\auditoria;
use Auth;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{

    private $rolPermisoController;

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

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(5);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a pantalla de auditoria sin permiso', 'Auditoria', '-', '-');

            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }


        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a pantalla de auditoria', 'Auditoria', '-', '-');
        return view('auditoria.index');
    }

    public function getAllAudits()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(6);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $auditorias = auditoria::paginate(10);
        return response()->json($auditorias);
    }
}
