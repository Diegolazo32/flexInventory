<?php

namespace App\Http\Controllers;

use App\Models\caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    private $rolPermisoController;

    public function getAllCajas(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(21);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $cajas = caja::where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('ubicacion', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($cajas);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $cajas = caja::paginate($request->per_page);
            return response()->json($cajas);
        }

        if ($request->onlyActive) {
            $cajas = caja::where('estado', 1)->get();
            return response()->json($cajas);
        }

        $cajas = caja::all();
        return response()->json($cajas);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(17);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('cajas.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(18);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $caja = new caja();
            $caja->nombre = $request->nombre;
            $caja->ubicacion = $request->ubicacion;
            $caja->estado = 1;
            $caja->save();

            return response()->json(['success' => 'Caja creada correctamente']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(18);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $caja = caja::find($request->id);
            $caja->nombre = $request->nombre;
            $caja->ubicacion = $request->ubicacion;
            $caja->estado = $request->estado;
            $caja->update();

            return response()->json(['success' => 'Caja actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function delete($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(18);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $caja = caja::find($id);
            $caja->delete();

            return response()->json(['success' => 'Caja eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
