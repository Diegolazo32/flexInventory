<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientesController extends Controller
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
        return view('clientes.index');
    }

    public function getAllClientes()
    {
        $this->checkRole();
        $clientes = clientes::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $this->checkRole();
        try {

            $cliente = new clientes();
            $cliente->nombre = $request->nombre;
            $cliente->apellido = $request->apellido;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->DUI = $request->DUI;
            $cliente->descuento = $request->descuento;
            $cliente->estado = 1;

            $cliente->save();

            return response()->json(['success' => 'Cliente guardado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar el cliente']);
        }
    }

    public function update(Request $request)
    {
        $this->checkRole();
        try {

            $cliente = clientes::find($request->id);
            $cliente->nombre = $request->nombre;
            $cliente->apellido = $request->apellido;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->DUI = $request->DUI;
            $cliente->descuento = $request->descuento;
            $cliente->estado = $request->estado;

            $cliente->save();

            return response()->json(['success' => 'Cliente actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el cliente']);
        }
    }

    public function delete($id)
    {
        $this->checkRole();
        try {
            $cliente = clientes::find($id);
            $cliente->delete();
            return response()->json(['success' => 'Cliente eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el cliente']);
        }
    }
}
