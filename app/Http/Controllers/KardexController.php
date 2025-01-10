<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\kardex;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    private $rolPermisoController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(19);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('kardex.index');
    }

    public function getAllKardex()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(19);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para realizar esta acción'], 403);
        }

        $kardex = Kardex::all();

        return response()->json($kardex);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(20);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para realizar esta acción'], 403);
        }

        $movimientos = $request->all();

        $inventario = inventario::where('estado', 1)->first();

        try {

            foreach ($movimientos as $movimiento) {
                $kardex = new kardex();
                $kardex->accion = $movimiento['accion'];
                $kardex->cantidad = $movimiento['cantidad'];
                $kardex->producto = $movimiento['id'];
                $kardex->inventario = $inventario->id;
                //$kardex->stockInicial = $movimiento['item']['stockInicial'];
                $kardex->observacion = $movimiento['observacion'];
                $kardex->save();
            }

            return response()->json(['success' => 'Movimientos guardados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar los movimientos'], 500);
        }
    }
}
