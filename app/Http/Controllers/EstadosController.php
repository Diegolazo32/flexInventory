<?php

namespace App\Http\Controllers;

use App\Models\estados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadosController extends Controller
{

    private $rolPermisoController;

    public function getAllEstados(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $estados = estados::where('descripcion', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($estados);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $estados = estados::paginate($request->per_page);
            return response()->json($estados);
        }

        $estados = estados::all();
        return response()->json($estados);

    }

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(37);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }
        return view('estados.index');
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(38);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

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
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(39);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

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
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(40);

        if (!$permiso) {
            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        try {
            $estado = estados::find($id);
            $estado->delete();
            return response()->json(['success' => 'Estado eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el estado']);
        }
    }
}
