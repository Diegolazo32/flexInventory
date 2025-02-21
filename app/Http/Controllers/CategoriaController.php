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
        $permiso = $this->rolPermisoController->checkPermisos(31);

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
        $permiso = $this->rolPermisoController->checkPermisos(27);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de acceso sin permiso', 'categorias', '-', '-');

            return redirect()->route('dashboard');
        }

        $auditoriaController->registrarEvento(Auth::user()->usuario, 'Acceso a vista de categorias', 'categorias', '-', '-');
        return view('categorias.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(28);
        $auditoriaController = new AuditoriaController();


        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de crear sin permiso', 'categorias', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'descripcion' => 'required',
        ]);


        try {
            $categoria = new categoria();
            $categoria->descripcion = $request->descripcion;
            $categoria->estado = 1;
            $categoria->save();

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Creacion de categoria', 'categorias', '-', $categoria);
            return response()->json(['success' => 'Categoria creada correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Error al crear categoria', 'categorias', '-', '-');
            return response()->json(['error' => 'Error al crear la categoria']);
        }
    }

    public function update(Request $request, $id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(29);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de actualizar sin permiso', 'categorias', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'descripcion' => 'required',
            'estado' => 'required',
        ]);


        try {
            $oldCategoria = categoria::find($id);
            $categoria = categoria::find($id);
            $categoria->descripcion = $request->descripcion;
            $categoria->estado = $request->estado;
            $categoria->save();

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Actualizacion de categoria', 'categorias', $oldCategoria, $categoria);
            return response()->json(['success' => 'Categoria actualizada correctamente']);
        } catch (\Exception $e) {
            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Error al actualizar categoria', 'categorias', '-', '-');
            return response()->json(['error' => 'Error al actualizar la categoria']);
        }
    }

    public function delete($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(30);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de eliminar sin permiso', 'categorias', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }


        try {
            $oldDelete = categoria::find($id);
            $categoria = categoria::find($id);
            $categoria->delete();

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Eliminacion de categoria', 'categorias', $oldDelete, '-');
            return response()->json(['success' => 'Categoria eliminada correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Error al eliminar categoria', 'categorias', '-', '-');
            return response()->json(['error' => 'Error al eliminar la categoria']);
        }
    }
}
