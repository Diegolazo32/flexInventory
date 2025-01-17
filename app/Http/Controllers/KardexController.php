<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\kardex;
use App\Models\productos;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    private $rolPermisoController;

    private $inventarioActivo;

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

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $inventario = inventario::where('estado', 3)->first();

        $kardex = Kardex::where('inventario', $inventario->id)->get();

        return response()->json($kardex);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(20);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para realizar esta acción'], 403);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $movimientos = $request->all();

        $inventario = inventario::where('estado', 3)->first();

        try {

            foreach ($movimientos as $movimiento) {
                $kardex = new kardex();
                $kardex->accion = $movimiento['accion'];
                $kardex->cantidad = $movimiento['cantidad'];
                $kardex->producto = $movimiento['id'];
                $kardex->inventario = $inventario->id;
                //$kardex->stockInicial = $movimiento['item']['stockInicial'];

                if ($movimiento['observacion'] == null) {
                    $kardex->observacion = 'Movimiento de stock';
                } else {
                    $kardex->observacion = $movimiento['observacion'];
                }

                $kardex->save();

                //Actualizar stock
                $producto = productos::find($movimiento['id']);
                if ($movimiento['accion'] == 1) {
                    $producto->stock = $producto->stock + $movimiento['cantidad'];
                } else {
                    $producto->stock = $producto->stock - $movimiento['cantidad'];
                }

                $producto->save();
            }

            return response()->json(['success' => 'Movimientos guardados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar los movimientos'], 500);
        }
    }

    public function searchKardex(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(19);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para realizar esta acción'], 403);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $kardex = kardex::where('descripcion', 'like', '%' . $request->search . '%')->get();
        return response()->json($kardex);
    }
}
