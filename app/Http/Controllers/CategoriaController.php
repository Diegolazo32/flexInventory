<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
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

    public function getAllCategorias()
    {
        $categorias = categoria::all();
        return response()->json($categorias);
    }

    public function index()
    {
        $this->checkRole();
        return view('categorias.index');
    }

    public function store(Request $request)
    {
        $this->checkRole();

        try {
            $categoria = new categoria();
            $categoria->descripcion = $request->descripcion;
            $categoria->estado = 1;
            $categoria->save();
            return response()->json(['success' => 'Categoria creada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la categoria']);
        }
    }

    public function update(Request $request, $id)
    {
        $this->checkRole();

        try {
            $categoria = categoria::find($id);
            $categoria->descripcion = $request->descripcion;
            $categoria->estado = $request->estado;
            $categoria->save();
            return response()->json(['success' => 'Categoria actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la categoria']);
        }
    }

    public function delete($id)
    {
        $this->checkRole();

        try {
            $categoria = categoria::find($id);
            $categoria->delete();
            return response()->json(['success' => 'Categoria eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la categoria']);
        }
    }
}
