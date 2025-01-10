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

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('proveedores.index');
    }

    public function getAllProveedores()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(52);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $proveedores = proveedores::all();
        return response()->json($proveedores);
    }
    public function getAllPaginatedProveedores(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(52);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $proveedores = proveedores::paginate($request->numItems);
        return response()->json($proveedores);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

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
            return response()->json(['success' => 'Proveedor guardado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar el proveedor']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
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
            return response()->json(['success' => 'Proveedor actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el proveedor']);
        }
    }

    public function delete(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(41);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $proveedor = proveedores::find($request->id);
            $proveedor->delete();
            return response()->json(['success' => 'Proveedor eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el proveedor']);
        }
    }
}
