<?php

namespace App\Http\Controllers;

use App\Models\roles;
use App\Models\rolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{

    private $rolPermisoController;

    public function getAllRoles(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(15);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $roles = roles::where('descripcion', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($roles);
        }

        if ($request->onlyActive) {
            $roles = roles::where('estado', 1)->get();
            return response()->json($roles);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $roles = roles::paginate($request->per_page);
            return response()->json($roles);
        }

        $roles = roles::all();
        return response()->json($roles);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(11);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('roles.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(12);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $rol = new roles();
            $rol->descripcion = $request->descripcion;
            $rol->estado = 1;
            $rol->save();
            return response()->json(['success' => 'Rol creado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el rol']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(13);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $rol = roles::find($request->id);
            $rol->descripcion = $request->descripcion;
            $rol->estado = $request->estado;
            $rol->save();
            return response()->json(['success' => 'Rol actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el rol']);
        }
    }

    public function delete(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(14);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $rol = roles::find($request->id);
            $rol->delete();
            return response()->json(['success' => 'Rol eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el rol']);
        }
    }

    public function permisos(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(16);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            //Obtiene todos los permisos del rol en la tabla rolPermiso
            $rolpermiso = rolPermiso::where('rol', $request->id)->get();
            //Permisos del rol entrantes
            $permisosDelRol = $request->permisos;

            //Si el rol tiene permisos
            if ($rolpermiso->count() > 0) {
                //Elimina los permisos del rol
                rolPermiso::where('rol', $request->id)->delete();
            }

            //Si el rol tiene permisos entrantes
            if ($permisosDelRol) {
                //Recorre los permisos entrantes
                foreach ($permisosDelRol as $permiso) {
                    //Crea un nuevo rolPermiso
                    $rolPermiso = new rolPermiso();
                    //Asigna el rol
                    $rolPermiso->rol = $request->id;
                    //Asigna el permiso
                    $rolPermiso->permiso = $permiso;
                    //Guarda el rolPermiso
                    $rolPermiso->save();
                }
            }

            //Retorna un mensaje de éxito
            return response()->json(['success' => 'Permisos asignados correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al asignar permisos'], 400);
        }
    }
}
