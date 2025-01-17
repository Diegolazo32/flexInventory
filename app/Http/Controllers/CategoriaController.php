<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    private $rolPermisoController;


    public function getAllCategorias(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(21);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $categorias = categoria::where('descripcion', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($categorias);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $categorias = categoria::paginate($request->per_page);
            return response()->json($categorias);
        }

        if ($request->onlyActive) {
            $categorias = categoria::where('estado', 1)->get();
            return response()->json($categorias);
        }

        $categorias = categoria::all();
        return response()->json($categorias);
    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(17);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('categorias.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(18);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }


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
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(19);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }


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
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(20);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }


        try {
            $categoria = categoria::find($id);
            $categoria->delete();
            return response()->json(['success' => 'Categoria eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la categoria']);
        }
    }
}
