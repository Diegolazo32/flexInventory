<?php

namespace App\Http\Controllers;

use App\Models\estados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadosController extends Controller
{

    private $rolPermisoController;

    public function getAllEstados(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $estados = estados::where('descripcion', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($estados);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $estados = estados::paginate($request->per_page);
            return response()->json($estados);
        }

        $estados = estados::all();
        return response()->json($estados);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(37);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de acceso a la pantalla de estados sin permiso', 'Estados', '-', '-');
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Ingreso a la pantalla de estados', 'Estados', '-', '-');
        return view('estados.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(38);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear estado sin permiso', 'Estados', '-', '-');
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        try {
            $estado = new estados();
            $estado->descripcion = $request->descripcion;
            $estado->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de estado', 'Estados', '-', $estado);
            return response()->json(['success' => 'Estado creado correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al crear estado', 'Estados', '-', '-');
            return response()->json(['error' => 'Error al crear el estado']);
        }
    }

    public function update(Request $request, $id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(39);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de modificar estado sin permiso', 'Estados', '-', '-');
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        try {
            $oldEstado = estados::find($id);
            $estado = estados::find($id);
            $estado->descripcion = $request->descripcion;
            $estado->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Actualización de estado', 'Estados', $oldEstado, $estado);
            return response()->json(['success' => 'Estado actualizado correctamente']);
        } catch (\Exception $e) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al actualizar estado', 'Estados', '-', '-');
            return response()->json(['error' => 'Error al actualizar el estado']);
        }
    }

    public function delete($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(40);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de eliminar sin permiso', 'Estados', '-', '-');
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        try {
            $oldEstado = estados::find($id);
            $estado = estados::find($id);
            $estado->delete();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Eliminación de estado', 'Estados', $oldEstado, '-');
            return response()->json(['success' => 'Estado eliminado correctamente']);
        } catch (\Exception $e) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al eliminar estado', 'Estados', '-', '-');
            return response()->json(['error' => 'Error al eliminar el estado']);
        }
    }
}
