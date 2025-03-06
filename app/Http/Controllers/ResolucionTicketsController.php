<?php

namespace App\Http\Controllers;

use App\Models\resolucionTickets;
use Illuminate\Http\Request;

class ResolucionTicketsController extends Controller
{
    private $rolPermisoController;
    private $inventarioActivo;

    public function index()
    {
        return view('resolucionTickets.index');
    }

    public function getAllTickets()
    {
        $tickets = resolucionTickets::all();
        return response()->json($tickets);
    }

    public function store(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59); //Cambiar el permiso


        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        $request->validate([
            'resolucion' => 'required',
            'fecha' => 'required',
            'desde' => 'required',
            'hasta' => 'required',
            'serie' => 'required',
            'autorizacion' => 'required',
        ]);

        try
        {

            $oldResolucion = resolucionTickets::where('estado', 1)->first();

            if ($oldResolucion)
            {
                $oldResolucion->estado = 2;
                $oldResolucion->save();
            }

            $resolucion = new resolucionTickets();
            $resolucion->resolucion = $request->resolucion;
            $resolucion->fecha = $request->fecha;
            $resolucion->desde = $request->desde;
            $resolucion->hasta = $request->hasta;
            $resolucion->serie = $request->serie;
            $resolucion->autorizacion = $request->autorizacion;
            $resolucion->estado = 1;
            $resolucion->save();

            return response()->json(['success' => 'Resolución de tickets creada correctamente']);

        }
        catch (\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
