<?php

namespace App\Http\Controllers;

use App\Models\tipoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoVentaController extends Controller
{
    private $rolPermisoController;

    public function getAllTipoVenta(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        if ($request->onlyActive) {
            $tipoventa = tipoVenta::where('estado', 1)->get();
        } else {
            $tipoventa = tipoVenta::all();
        }

        return response()->json($tipoventa);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        return view('tipoventa.index');
    }
}
