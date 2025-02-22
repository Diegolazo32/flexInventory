<?php

namespace App\Http\Controllers;

use App\Models\clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientesController extends Controller
{
    private $rolPermisoController;
    private $auditoriaController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(46);
        $auditoriaController = new AuditoriaController();


        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de acceso sin permiso', 'clientes', '-', '-');
            return redirect()->route('dashboard');
        }


        $auditoriaController->registrarEvento(Auth::user()->usuario, 'Acceso a pantalla de clientes', 'clientes', '-', '-');
        return view('clientes.index');
    }

    public function getAllClientes(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(50);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $clientes = clientes::where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('apellido', 'like', '%' . $request->search . '%')
                ->orWhere('telefono', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('DUI', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            return response()->json($clientes);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $clientes = clientes::paginate($request->per_page);
            return response()->json($clientes);
        }

        if ($request->onlyActive) {
            $clientes = clientes::where('estado', 1)->get();
            return response()->json($clientes);
        }

        $clientes = clientes::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(47);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de crear sin permiso', 'clientes', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'DUI' => 'required',
            'descuento' => 'required',
        ]);



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


            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Creación de cliente', 'clientes', $cliente->id, $cliente->nombre . ' ' . $cliente->apellido);
            return response()->json(['success' => 'Cliente guardado correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Error al crear cliente', 'clientes', '-', '-');
            return response()->json(['error' => 'Error al guardar el cliente']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(48);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de actualizar sin permiso', 'clientes', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate([
            'id' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'email' => 'required',
            'DUI' => 'required',
            //'descuento' => 'required|nullable',
            'estado' => 'required',
        ]);

        if ($request->descuento  == null) {
            $descuento = 0;
        } else {
            $descuento = $request->descuento;
        }

        try {

            $oldCliente = clientes::find($request->id);
            $cliente = clientes::find($request->id);
            $cliente->nombre = $request->nombre;
            $cliente->apellido = $request->apellido;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->DUI = $request->DUI;
            $cliente->descuento = $descuento;
            $cliente->estado = $request->estado;

            $cliente->save();

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Actualización de cliente', 'clientes', $oldCliente, $cliente);

            return response()->json(['success' => 'Cliente actualizado correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Error al actualizar cliente', 'clientes', '-', '-');
            return response()->json(['error' => 'Error al actualizar el cliente']);
        }
    }

    public function delete($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(49);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Intento de eliminar sin permiso', 'clientes', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $oldCliente = clientes::find($id);
            $cliente = clientes::find($id);
            $cliente->delete();

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Eliminación de cliente', 'clientes', $oldCliente, '-');
            return response()->json(['success' => 'Cliente eliminado correctamente']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->usuario, 'Error al eliminar cliente', 'clientes', '-', '-');
            return response()->json(['error' => 'Error al eliminar el cliente']);
        }
    }
}
