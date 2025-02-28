<?php

namespace App\Http\Controllers;

use App\Models\unidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnidadesController extends Controller
{

    private $rolPermisoController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(22);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a pantalla de unidades sin permiso', 'Unidades', '-', '-');
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a pantalla de unidades', 'Unidades', '-', '-');
        return view('unidades.index');
    }

    public function getAllUnidades(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(26);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $unidades = unidades::where('descripcion', 'like', '%' . $request->search . '%')
                ->orWhere('abreviatura', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($unidades);
        }

        if ($request->onlyActive) {
            $unidades = unidades::where('estado', 1)->get();
            return response()->json($unidades);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $unidades = unidades::paginate($request->per_page);
            return response()->json($unidades);
        }

        $unidades = unidades::all();
        return response()->json($unidades);
    }

    public function store(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(23);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de crear unidad sin permiso', 'Unidades', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate(
            [
                'descripcion' => 'required|unique:unidades',
                'abreviatura' => 'required|unique:unidades',
            ],
            [
                'descripcion.required' => 'La descripción es requerida',
                'descripcion.unique' => 'La descripción ya existe',
                'abreviatura.required' => 'La abreviatura es requerida',
                'abreviatura.unique' => 'La abreviatura ya existe',
            ]
        );

        try {
            $unidades = new unidades();
            $unidades->descripcion = $request->descripcion;
            $unidades->abreviatura = $request->abreviatura;
            $unidades->estado = 1;
            $unidades->save();
            $auditoria->registrarEvento(Auth::user()->nombre, 'Creacion de unidad', 'Unidades', '-', $unidades);
            return response()->json(['success' => 'Unidad creada correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al crear unidad', 'Unidades', '-', '-');
            return response()->json(['error' => 'Error al crear la unidad']);
        }
    }

    public function update(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(24);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de actualizar unidad sin permiso', 'Unidades', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate(
            [
                'descripcion' => 'required',
                'abreviatura' => 'required',
            ],
            [
                'descripcion.required' => 'La descripción es requerida',
                'abreviatura.required' => 'La abreviatura es requerida',
            ]
        );

        try {
            $oldUnidad = unidades::find($request->id);
            $unidades = unidades::find($request->id);
            $unidades->descripcion = $request->descripcion;
            $unidades->abreviatura = $request->abreviatura;
            $unidades->estado = $request->estado;
            $unidades->save();
            $auditoria->registrarEvento(Auth::user()->nombre, 'Actualizacion de unidad', 'Unidades', $oldUnidad, $unidades);

            return response()->json(['success' => 'Unidad actualizada correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al actualizar unidad', 'Unidades', '-', '-');
            return response()->json(['error' => 'Error al actualizar la unidad']);
        }
    }

    public function delete(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(25);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de eliminar unidad sin permiso', 'Unidades', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $unidades = unidades::find($request->id);
            $unidades->delete();
            $auditoria->registrarEvento(Auth::user()->nombre, 'Eliminar unidad', 'Unidades', $unidades, '-');
            return response()->json(['success' => 'Unidad eliminada correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al eliminar unidad', 'Unidades', '-', '-');
            return response()->json(['error' => 'Error al eliminar la unidad']);
        }
    }
}
