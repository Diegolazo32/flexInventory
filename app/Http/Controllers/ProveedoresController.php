<?php

namespace App\Http\Controllers;

use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProveedoresController extends Controller
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

    public function index()
    {
        $this->checkRole();
        return view('proveedores.index');
    }

    public function getAllProveedores()
    {
        $this->checkRole();
        $proveedores = proveedores::all();
        return response()->json($proveedores);
    }

    public function store(Request $request)
    {
        $this->checkRole();
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
        $this->checkRole();
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
        $this->checkRole();
        try {
            $proveedor = proveedores::find($request->id);
            $proveedor->delete();
            return response()->json(['success' => 'Proveedor eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el proveedor']);
        }
    }
}
