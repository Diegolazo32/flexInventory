<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\kardex;
use App\Models\lotes;
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

                //dd($producto);

                if ($movimiento['accion'] == 1) {
                    //Sumarle al lote mas reciente hasta agotar la cantidad
                    $cantidad = $movimiento['cantidad'];

                    //dd($cantidad);

                    //Obtener lote mas reciente del producto
                    $lote = lotes::where('producto', $producto->id)->where('estado', 1)->orderBy('created_at', 'desc')->first();

                    //$cantidadInicalLote = $lote->cantidad;

                    if ($cantidad > 0) {
                        $lote->cantidad = $lote->cantidad + $cantidad;
                        $lote->save();
                    }

                    //Actualizar el stock del producto sumando todos los lotes que tengan stock
                    $producto->stock = lotes::where('producto', $producto->id)->where('estado', 1)->sum('cantidad');


                } else {
                    //Descontarle al lote mas antiguo hasta agotarlo, y luego seguir con el siguiente lote
                    $cantidad = $movimiento['cantidad'];

                    //Obtener todos los lotes activos del producto ordenador por el mas antiguo primero
                    $lotes = lotes::where('producto', $producto->id)->where('estado', 1)->orderBy('created_at', 'asc')->get();

                    foreach ($lotes as $lote) {
                        if ($cantidad > 0) {
                            if ($lote->cantidad >= $cantidad) {
                                $lote->cantidad = $lote->cantidad - $cantidad;
                                $lote->save();
                                $cantidad = 0;
                            } else {
                                $cantidad = $cantidad - $lote->cantidad;
                                $lote->cantidad = 0;
                                $lote->estado = 2;
                                $lote->save();
                            }
                        }
                    }

                    //Actualizar el stock del producto sumando todos los lotes que tengan stock
                    $producto->stock = lotes::where('producto', $producto->id)->where('estado', 1)->sum('cantidad');
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

    public function storeWithProduct($producto, $request, $inventario)
    {
        $kardex = new Kardex();
        $kardex->producto = $producto->id;
        $kardex->cantidad = $request->stock;
        $kardex->accion = 1;
        $kardex->inventario = $inventario->id;
        $kardex->observacion = 'Inventario inicial';
        $kardex->save();
    }
}
