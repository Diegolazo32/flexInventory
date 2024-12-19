<?php

namespace App\Http\Controllers;

use App\Models\permisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermisosController extends Controller
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

    public function getAllPermisos()
    {
        $this->checkRole();
        $permissions = permisos::all();
        return response()->json($permissions);
    }

    public function index()
    {
        $this->checkRole();
        return view('permisos.index');
    }

    public function store(Request $request)
    {
        $this->checkRole();

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $permiso = new permisos();
            $permiso->nombre = $request->nombre;
            $permiso->ruta = $request->ruta;
            $permiso->descripcion = $request->descripcion;
            $permiso->grupo = $request->grupo;
            $permiso->endpoint = $request->endpoint;
            $permiso->metodo = $request->metodo;

            $permiso->save();
            return response()->json(['success' => 'Permiso creado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el permiso']);
        }
    }

    public function update(Request $request)
    {
        $this->checkRole();

        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        try {
            $permiso = permisos::find($request->id);
            $permiso->nombre = $request->nombre;
            $permiso->ruta = $request->ruta;
            $permiso->descripcion = $request->descripcion;
            $permiso->grupo = $request->grupo;
            $permiso->endpoint = $request->endpoint;
            $permiso->metodo = $request->metodo;
            $permiso->save();
            return response()->json(['success' => 'Permiso actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el permiso']);
        }
    }

    public function delete($id)
    {
        $this->checkRole();

        try {
            $permiso = permisos::find($id);
            $permiso->delete();
            return response()->json(['success' => 'Permiso eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el permiso']);
        }
    }
}
