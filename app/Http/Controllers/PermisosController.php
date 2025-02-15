<?php

namespace App\Http\Controllers;

use App\Models\permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermisosController extends Controller
{

    private $rolPermisoController;

    public function getAllPermisos(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(21);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $permissions = permisos::where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('descripcion', 'like', '%' . $request->search . '%')
                ->orWhere('grupo', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($permissions);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $permissions = permisos::paginate($request->per_page);
            return response()->json($permissions);
        }

        if ($request->onlyActive) {
            $permissions = permisos::where('estado', 1)->get();
            return response()->json($permissions);
        }

        $permissions = permisos::all();
        return response()->json($permissions);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(17);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('permisos.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(18);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $permiso = new permisos();
            $permiso->nombre = $request->nombre;
            //$permiso->ruta = $request->ruta;
            $permiso->descripcion = $request->descripcion;
            $permiso->grupo = $request->grupo;
            //$permiso->endpoint = $request->endpoint;
            //$permiso->metodo = $request->metodo;

            $permiso->save();
            return response()->json(['success' => 'Permiso creado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el permiso']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(19);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $permiso = permisos::find($request->id);
            $permiso->nombre = $request->nombre;
            //$permiso->ruta = $request->ruta;
            $permiso->descripcion = $request->descripcion;
            $permiso->grupo = $request->grupo;
            //$permiso->endpoint = $request->endpoint;
            //$permiso->metodo = $request->metodo;
            $permiso->save();
            return response()->json(['success' => 'Permiso actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el permiso']);
        }
    }

    public function delete($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(20);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $permiso = permisos::find($id);
            $permiso->delete();
            return response()->json(['success' => 'Permiso eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el permiso']);
        }
    }
}
