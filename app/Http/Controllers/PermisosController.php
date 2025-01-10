<?php

namespace App\Http\Controllers;

use App\Models\permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermisosController extends Controller
{

    private $rolPermisoController;

    public function getAllPermisos()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(46);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $permissions = permisos::all();
        return response()->json($permissions);
    }

    public function getAllPaginatedPermisos(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(46);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $permissions = permisos::paginate($request->per_page);
        return response()->json($permissions);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(42);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('permisos.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(43);

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
        $permiso = $this->rolPermisoController->checkPermisos(44);

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
        $permiso = $this->rolPermisoController->checkPermisos(45);

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
