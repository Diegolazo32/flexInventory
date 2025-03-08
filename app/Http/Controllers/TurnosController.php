<?php

namespace App\Http\Controllers;

use App\Models\caja;
use App\Models\turnos;
use App\Models\ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnosController extends Controller
{
    private $rolPermisoController;
    private $inventarioActivo;
    public function getAllActiveTurnos()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59); //Cambiar el permiso


        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección'], 403);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            flash('No hay inventario activo', 'error');
            return response()->json(['error' => 'No hay inventario activo'], 403);
        }

        $turnos = turnos::join('users', 'turnos.vendedor', '=', 'users.id')
            ->join('cajas', 'turnos.caja', '=', 'cajas.id')
            ->join('estados', 'turnos.estado', '=', 'estados.id')
            ->select('turnos.*', 'users.nombre as vendedor', 'cajas.nombre as caja', 'estados.descripcion as estado')
            ->where('turnos.estado', 1)
            ->get();
        return $turnos;
    }

    public function startTurno(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59); //Cambiar el permiso


        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección'], 403);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay inventario activo'], 403);
        }

        try {

            $vendedor = Auth::user()->id;
            $turno = turnos::where('vendedor', $vendedor)->where('estado', 1)->first();

            if ($turno) {
                return response()->json(['error' => 'Ya tienes un turno activo']);
            }

            $turno = new turnos();
            $turno->vendedor = $vendedor;
            $turno->caja = $request->caja;
            $turno->apertura = $request->apertura;
            $turno->montoInicial = $request->montoInicial;
            $turno->estado = 1;
            $turno->montoCierre = $request->montoInicial;
            $turno->save();

            //Marcar caja como ocupada
            $caja = caja::find($request->caja);
            $caja->estado = 10;
            $caja->save();

            return response()->json(['success' => 'Turno iniciado correctamente'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function endTurno(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59); //Cambiar el permiso


        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay inventario activo'], 403);
        }

        try {

            dd($request->all());

            return response()->json(['success' => 'Turno finalizado correctamente'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
