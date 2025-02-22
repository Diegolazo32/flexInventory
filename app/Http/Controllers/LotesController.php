<?php

namespace App\Http\Controllers;

use App\Models\lotes;
use App\Models\productos;
use Auth;
use Illuminate\Http\Request;

class LotesController extends Controller
{
    private $rolPermisoController;
    private $inventarioActivo;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(60);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a pantalla de lotes sin permiso', 'Lotes', '-', '-');
            return redirect()->route('dashboard');
        }

        $auditoria->registrarEvento(Auth::user()->nombre, 'Ingreso a la pantalla de lotes', 'Lotes', '-', '-');
        return view('lotes.index');
    }

    public function storeWithProduct($producto, $request, $inventario)
    {
        $auditoria = new AuditoriaController();


        //Crer lote
        $lote = new lotes();
        $lote->codigo = 'L' . $producto->codigo . $producto->id . '-' . date('Y-m-d') . date('H:i:s');
        $lote->numero = 1;
        $lote->cantidad = $request->stock;
        $lote->fechaVencimiento = $request->fechaVencimiento;
        $lote->producto = $producto->id;
        $lote->estado = 1;
        $lote->inventario = $inventario->id;
        $lote->save();

        $auditoria->registrarEvento(Auth::user()->nombre, 'Creacion de nuevo lote', 'Lotes', '-', $lote);


    }

    public function getAllLotes()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(63);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        //All lotes sort by producto
        $lotes = lotes::orderBy('producto', 'asc')->paginate(5);
        return response()->json($lotes);
    }

    public function getLotesByInventario($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(63);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $lotes = lotes::where('inventario', $id)->get();
        return response()->json($lotes);
    }

    public function getLotesByProduct($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(63);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $lotes = lotes::where('producto', $id)->get();
        return response()->json($lotes);
    }

    public function actualizarLotes(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(62);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de actualizar lotes sin permiso', 'Lotes', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $lotes = $request->lotes;

        try {
            foreach ($lotes as $lote) {
                $loteUpdate = lotes::find($lote['id']);
                //$loteUpdate->cantidad = $lote['cantidad'];
                $loteUpdate->fechaVencimiento = $lote['fechaVencimiento'];
                $loteUpdate->save();

                //Asignar fecha de vencimiento mas proxima al producto
                $producto = productos::find($loteUpdate->producto);
                $lotes = lotes::where('producto', $producto->id)->get();
                $fechaVencimiento = null;

                foreach ($lotes as $lote) {
                    if ($fechaVencimiento == null) {
                        $fechaVencimiento = $lote->fechaVencimiento;
                    } else {
                        if ($lote->fechaVencimiento < $fechaVencimiento) {
                            $fechaVencimiento = $lote->fechaVencimiento;
                        }
                    }
                }

                $producto->fechaVencimiento = $fechaVencimiento;
                $producto->save();
            }

            $auditoria->registrarEvento(Auth::user()->nombre, 'Actualizacion de lote', 'Lotes', $lotes, '-');
            return response()->json(['success' => 'Lotes actualizados correctamente']);
        } catch (\Exception $e) {

            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al actualizar lotes', '', '-', '-');
            return response()->json(['error' => 'Error al actualizar los lotes']);
        }
    }
}
