<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\productos;
use Illuminate\Http\Request;

class InventarioController extends Controller
{

    private $rolPermisoController;

    public function checkInventarioStatus()
    {
        $inventario = inventario::where('estado', 3)->orderBy('fechaApertura', 'desc')->first();

        if ($inventario) {
            return true;
        } else {
            return false;
        }
    }


    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(42);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('inventario.index');
    }

    public function getAllInventario(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(45);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $inventarioActivo = inventario::where('estado', 3)->orderBy('fechaApertura', 'desc')->first();
        $inventarioCerrado = inventario::where('estado', 4)->orderBy('fechaCierre', 'desc')->first();

        return response()->json(['inventarioActivo' => $inventarioActivo, 'inventarioCerrado' => $inventarioCerrado]);
    }

    public function open()
    {

        try {
            $this->rolPermisoController = new RolPermisoController();
            $permiso = $this->rolPermisoController->checkPermisos(43);

            if (!$permiso) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
            }

            $inventarioActivo = inventario::where('estado', 3)->orderBy('fechaApertura', 'desc')->first();

            if ($inventarioActivo) {
                return response()->json(['error' => 'Ya existe un inventario abierto']);
            }

            $inventario = new inventario();
            $inventario->fechaApertura = date('Y-m-d');
            $inventario->fechaCierre = null;
            $inventario->ProductosApertura = productos::all()->count();
            $inventario->StockApertura = productos::sum('stock');
            $inventario->ProductosCierre = 0;
            $inventario->StockCierre = 0;
            $inventario->totalInventario = 0;
            $inventario->aperturadoPor = auth()->user()->id;
            $inventario->cerradoPor = auth()->user()->id;
            $inventario->estado = 3;
            $inventario->save();

            $productos = productos::all();

            foreach ($productos as $producto) {
                $producto->stockInicial = $producto->stock;
                $producto->save();
            }

            return response()->json(['success' => 'Inventario abierto correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al abrir el inventario']);
        }
    }

    public function close(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);

        try {
            $this->rolPermisoController = new RolPermisoController();
            $permiso = $this->rolPermisoController->checkPermisos(44);

            if (!$permiso) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
            }

            $inventario = inventario::find($request->id);

            if ($inventario->estado == 4) {
                return response()->json(['error' => 'El inventario ya se encuentra cerrado']);
            }

            $totalInventario = 0;

            $productos = productos::all();

            foreach ($productos as $producto) {

                $totalInventario += $producto->precioCompra * $producto->stock;
            }

            $inventario->fechaCierre = date('Y-m-d');
            $inventario->cerradoPor = auth()->user()->id;
            $inventario->ProductosCierre = productos::all()->count();
            $inventario->StockCierre = productos::sum('stock');
            $inventario->totalInventario = $totalInventario;
            $inventario->estado = 4;
            $inventario->save();


            return response()->json(['success' => 'Inventario cerrado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cerrar el inventario']);
        }
    }
}
