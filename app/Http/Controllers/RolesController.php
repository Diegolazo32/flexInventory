<?php

namespace App\Http\Controllers;

use App\Models\roles;
use App\Models\rolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{

    public function checkRole()
    {
        try {
            if (Auth::user()->rol != 1) {
                flash('No tienes permisos para acceder a esta sección', 'error');
                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar el rol']);
        }
    }

    public function getAllRoles()
    {
        $this->checkRole();
        $roles = roles::all();
        return response()->json($roles);
    }

    public function index()
    {
        $this->checkRole();
        return view('roles.index');
    }

    public function store(Request $request)
    {
        $this->checkRole();

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
        $this->checkRole();

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
        $this->checkRole();

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
        $this->checkRole();

        //dd($request->all());

        try {
            //Encuentra el rol
            $rol = roles::find($request->id);
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
            return response()->json(['error' => 'Error al asignar permisos'],400);
        }
    }
}
