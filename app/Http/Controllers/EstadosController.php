<?php

namespace App\Http\Controllers;

use App\Models\estados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadosController extends Controller
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

    public function getAllEstados()
    {
        $estados = estados::all();
        return response()->json($estados);
    }

    public function index()
    {
        $this->checkRole();
        return view('estados.index');
    }

    public function store(Request $request)
    {
        $this->checkRole();

        try {
            $estado = new estados();
            $estado->descripcion = $request->descripcion;
            $estado->save();
            return response()->json(['success' => 'Estado creado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el estado']);
        }
    }

    public function update(Request $request, $id)
    {
        $this->checkRole();

        try {
            $estado = estados::find($id);
            $estado->descripcion = $request->descripcion;
            $estado->save();
            return response()->json(['success' => 'Estado actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el estado']);
        }
    }

    public function delete($id)
    {
        $this->checkRole();

        try {
            $estado = estados::find($id);
            $estado->delete();
            return response()->json(['success' => 'Estado eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el estado']);
        }
    }
}
