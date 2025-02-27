<?php

namespace App\Http\Controllers;

use App\Models\caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    private $rolPermisoController;
    private $auditoriaController;

    public function getAllCajas(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(36);

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
        $permiso = $this->rolPermisoController->checkPermisos(32);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de acceso sin permiso', 'cajas', '-', '-');
            return redirect()->route('dashboard');
        }


        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Acceso a pantalla de cajas', 'cajas', '-', '-');
        return view('cajas.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(33);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear sin permiso', 'cajas', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'nombre' => 'required',
            'ubicacion' => 'required',
        ]);

        try {
            $caja = new caja();
            $caja->nombre = $request->nombre;
            $caja->ubicacion = $request->ubicacion;
            $caja->estado = 1;
            $caja->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creacion de caja', 'cajas', '-', $caja);

            return response()->json(['success' => 'Caja creada correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al crear caja', 'cajas', '-', '-');
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(34);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de actualizar sin permiso', 'cajas', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'nombre' => 'required',
            'ubicacion' => 'required',
            'estado' => 'required',
        ]);

        try {

            $lastCaja = caja::find($request->id);
            $caja = caja::find($request->id);
            $caja->nombre = $request->nombre;
            $caja->ubicacion = $request->ubicacion;
            $caja->estado = $request->estado;
            $caja->update();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Actualizacion de caja', 'cajas', $lastCaja, $caja);
            return response()->json(['success' => 'Caja actualizada correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al actualizar caja', 'cajas', '-', '-');
            return response()->json($e->getMessage());
        }
    }

    public function delete($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(35);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de eliminar sin permiso', 'cajas', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $caja = caja::find($id);
            $deleteCaja = $caja;
            $caja->delete();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Eliminacion de caja', 'cajas', $deleteCaja, '-');
            return response()->json(['success' => 'Caja eliminada correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al eliminar caja', 'cajas', '-', '-');
            return response()->json($e->getMessage());
        }
    }
}
