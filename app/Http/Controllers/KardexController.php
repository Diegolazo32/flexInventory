<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\kardex;
use App\Models\lotes;
use App\Models\productos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    private $rolPermisoController;

    private $inventarioActivo;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(70);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a la pantalla de kardex sin permiso', 'Kardex', '-', '-');
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a la pantalla de kardex', 'Kardex', '-', '-');
        return view('kardex.index');
    }

    public function getAllKardex(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(27);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para realizar esta acción'], 403);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $inventario = inventario::where('estado', 3)->first();

        if ($request->has('per_page')) {
            //Paginate all the kardex movements regardless of the inventory
            $kardex = kardex::orderBy('created_at', 'desc')->paginate($request->per_page);
            return response()->json($kardex);
        }

        $kardex = Kardex::where('inventario', $inventario->id)->get();

        return response()->json($kardex);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(71);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de guardar movimientos de kardex sin permiso', 'Kardex', '-', '-');
            return response()->json(['error' => 'No tiene permisos para realizar esta acción'], 403);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de guardar movimientos de kardex sin inventario activo', 'Kardex', '-', '-');
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

                if ($movimiento['observacion'] == null) {
                    $kardex->observacion = 'Movimiento de stock';
                } else {
                    $kardex->observacion = $movimiento['observacion'];
                }

                $kardex->save();


                //Actualizar stock
                $producto = productos::find($movimiento['id']);

                if ($movimiento['accion'] == 1) {
                    //Sumarle al lote mas reciente hasta agotar la cantidad
                    $cantidad = $movimiento['cantidad'];

                    //Obtener lote mas reciente del producto
                    $lote = lotes::where('producto', $producto->id)->where('estado', 1)->orderBy('created_at', 'desc')->first();

                    if ($cantidad > 0) {
                        $lote->cantidad = $lote->cantidad + $cantidad;
                        $lote->save();
                    }

                    //Actualizar el stock del producto sumando todos los lotes que tengan stock
                    $producto->stock = lotes::where('producto', $producto->id)->where('estado', 1)->sum('cantidad');
                    $auditoria->registrarEvento(Auth::user()->nombre, 'Registro de movimiento de entrada de kardex - Ingreso', 'Kardex', '-', $kardex);
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

                    $auditoria->registrarEvento(Auth::user()->nombre, 'Registro de movimiento de salida de kardex - Salida', 'Kardex', '-', $kardex);
                }

                //Si el stock es menor al stock minimo, enviar notificación
                if ($producto->stock < $producto->stockMinimo) {
                    flash('El producto ' . $producto->nombre . ' tiene un stock menor al stock mínimo', 'warning');
                }

                //Si el producto tiene stock menor o igual a 0, enviar notificación y desactivar el producto
                if ($producto->stock <= 0) {
                    flash('El producto ' . $producto->nombre . ' tiene un stock menor o igual a 0', 'warning');
                    $producto->estado = 2;
                }

                $producto->save();
            }

            return response()->json(['success' => 'Movimientos guardados correctamente'], 200);
        } catch (\Exception $e) {

            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al registrar los movimientos del kardex', '', '-', '-');
            return response()->json(['error' => 'Error al guardar los movimientos'], 500);
        }
    }

    public function movimientos()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(70);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a la pantalla de movimientos de kardex sin permiso', 'Kardex', '-', '-');
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a la pantalla de kardex', 'Kardex', '-', '-');
        return view('kardex.movimientos');
    }
}
