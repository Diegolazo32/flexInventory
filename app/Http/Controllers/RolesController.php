<?php

namespace App\Http\Controllers;

use App\Models\roles;
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

}
