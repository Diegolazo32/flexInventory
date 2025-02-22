<?php

namespace App\Http\Controllers;

use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*Pendiente cambiar los id de los permisos de proveedores*/

class ProveedoresController extends Controller
{
    private $rolPermisoController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(51);
        $auditoria = new AuditoriaController();


        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a pantalla de proveedores sin permiso', 'Proveedores', '-', '-');
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a la pantalla de proveedores', 'Proveedores', '-', '-');
        return view('proveedores.index');
    }

    public function getAllProveedores(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(55);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $proveedores = proveedores::where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('NIT', 'like', '%' . $request->search . '%')
                //->orWhere('telefonoPrincipal', 'like', '%' . $request->search . '%')
                ->orWhere('emailPrincipal', 'like', '%' . $request->search . '%')
                ->orWhere('representante', 'like', '%' . $request->search . '%')
                //->orWhere('telefonoRepresentante', 'like', '%' . $request->search . '%')
                ->orWhere('emailRepresentante', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($proveedores);
        }

        if ($request->onlyActive) {
            $proveedores = proveedores::where('estado', 1)->get();
            return response()->json($proveedores);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $proveedores = proveedores::paginate($request->per_page);
            return response()->json($proveedores);
        }

        $proveedores = proveedores::all();
        return response()->json($proveedores);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(52);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de crear proveedor sin permiso', 'Proveedores', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'NIT' => 'required',
            'telefonoPrincipal' => 'required',
            'emailPrincipal' => 'required',
            'representante' => 'required',
            'telefonoRepresentante' => 'required',
            'emailRepresentante' => 'required',
        ]);

        try {
            $proveedor = new proveedores();
            $proveedor->nombre = $request->nombre;
            $proveedor->direccion = $request->direccion;
            $proveedor->NIT = $request->NIT;
            $proveedor->telefonoPrincipal = $request->telefonoPrincipal;
            $proveedor->emailPrincipal = $request->emailPrincipal;
            $proveedor->representante = $request->representante;
            $proveedor->telefonoRepresentante = $request->telefonoRepresentante;
            $proveedor->emailRepresentante = $request->emailRepresentante;
            $proveedor->estado = 1;
            $proveedor->save();

            $auditoria->registrarEvento(Auth::user()->nombre, 'Creacion de proveedor', 'Proveedores', '-', $proveedor);

            return response()->json(['success' => 'Proveedor guardado correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error en la creacion de proveedor', 'Proveedores', '-', '-');

            return response()->json(['error' => 'Error al guardar el proveedor']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(53);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de actualizar proveedor sin permiso', 'Proveedores', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'id' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'NIT' => 'required',
            'telefonoPrincipal' => 'required',
            'emailPrincipal' => 'required',
            'representante' => 'required',
            'telefonoRepresentante' => 'required',
            'emailRepresentante' => 'required',
            'estado' => 'required',
        ]);

        try {
            $oldProveedor = proveedores::find($request->id);
            $proveedor = proveedores::find($request->id);
            $proveedor->nombre = $request->nombre;
            $proveedor->direccion = $request->direccion;
            $proveedor->NIT = $request->NIT;
            $proveedor->telefonoPrincipal = $request->telefonoPrincipal;
            $proveedor->emailPrincipal = $request->emailPrincipal;
            $proveedor->representante = $request->representante;
            $proveedor->telefonoRepresentante = $request->telefonoRepresentante;
            $proveedor->emailRepresentante = $request->emailRepresentante;
            $proveedor->estado = $request->estado;
            $proveedor->save();

            $auditoria->registrarEvento(Auth::user()->nombre, 'Actualizacion de proveedor', 'Proveedores', $oldProveedor, $proveedor);
            return response()->json(['success' => 'Proveedor actualizado correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al actualizar proveedor', 'Proveedores', '-', '-');
            return response()->json(['error' => 'Error al actualizar el proveedor']);
        }
    }

    public function delete(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(54);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de eliminar sin permiso', 'Proveedores', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $proveedor = proveedores::find($request->id);
            $proveedor->delete();

            $auditoria->registrarEvento(Auth::user()->nombre, 'Eliminacion de proveedor', 'Proveedores', $proveedor, '-');
            return response()->json(['success' => 'Proveedor eliminado correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al eliminar proveedor', 'Proveedores', '-', '-');
            return response()->json(['error' => 'Error al eliminar el proveedor']);
        }
    }
}
