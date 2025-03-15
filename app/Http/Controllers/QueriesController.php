<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueriesController extends Controller
{
    private $rolPermisoController;
    private $inventarioActivo;

    //Funcion para obtener lotes a 7 dias de vencimiento
    public function getLotesVencimiento()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(63);
        $auditoria = new AuditoriaController();


        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de obtener lotes por vencer sin permiso', 'Productos', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de obtener lotes por vencer sin inventario activo', 'Productos', '-', '-');
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $lotes = DB::table('lotes')
            ->join('productos', 'lotes.producto', '=', 'productos.id')
            ->select('lotes.id', 'lotes.numero', 'lotes.producto', 'lotes.cantidad', 'lotes.fechaVencimiento', 'productos.nombre')
            ->select('lotes.*', 'productos.nombre as producto')
            ->where('lotes.fechaVencimiento', '<=', date('Y-m-d', strtotime('+7 days')))
            ->where('lotes.estado', '=', 1)
            ->limit(5)
            ->get();

        return response()->json($lotes);
    }

    //Funcion para obtener productos con stock minimo
    public function getProductosStockMinimo()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59);
        $auditoria = new AuditoriaController();


        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de obtener productos por agotarse sin permiso', 'Productos', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de obtener lotes por agotarse sin inventario activo', 'Productos', '-', '-');
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $productos = DB::table('productos')
            ->select('id', 'nombre', 'stock', 'stockMinimo')
            ->where('estado', '=', 1)
            ->where('stockMinimo', '>=', DB::raw('stock'))
            ->limit(5)
            ->get();

        return response()->json($productos);

    }

    //Funcion para obtener productos con overstock o cerca de tenerlo
    public function getProductosOverstock()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de obtener productos con overstock sin permiso', 'Productos', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de obtener lotes con overstock sin inventario activo', 'Productos', '-', '-');
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $productos = DB::table('productos')
            ->select('id', 'nombre', 'stock', 'stockMaximo')
            ->where('estado', '=', 1)
            ->where('stockMaximo', '<=', DB::raw('stock'))
            ->where('estado', '=', 1)
            ->limit(5)
            ->get();

        return response()->json($productos);

    }

}
