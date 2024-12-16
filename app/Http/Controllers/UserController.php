<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class UserController extends Controller
{

    public function checkRole()
    {
        if (Auth::user()->rol != 1) {
            flash('No tienes permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }
    }

    public function getAllUsers()
    {

        $this->checkRole();

        $users = User::all();

        foreach ($users as $user) {
            //$user->fechaNacimiento = date('d/m/Y', strtotime($user->fechaNacimiento));

            if ($user->password == Hash::check('0000', $user->password)) {
                $user->hasPassword = false;
            } else {
                $user->hasPassword = true;
            }
        }

        return response()->json($users);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkRole();

        //Si el request tiene un campo de busqueda
        if ($request->has('search')) {

            if ($request->search == null) {
                return redirect()->route('users');
            }

            $users = User::where('nombre', 'like', "%{$request->search}%");

            if ($users->count() == 0) {
                flash('No se encontraron resultados', 'error');
                return redirect()->back();
            }

            $users = $users->paginate(10);
        } else {
            $users = User::paginate(10);
        }

        if ($request->has('clear')) {
            return redirect()->route('users');
        }

        return view('Users.index')->with([
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkRole();

        $request->validate(
            [
                'nombre' => 'required',
                'apellido' => 'required',
                'genero' => 'required',
                'usuario' => 'required',
                'rol' => 'required',
            ],
            [
                'nombre.required' => 'El campo nombre es requerido',
                'apellido.required' => 'El campo apellido es requerido',
                'genero.required' => 'El campo genero es requerido',
                'usuario.required' => 'El campo usuario es requerido',
                'rol.required' => 'El campo rol es requerido',
            ]
        );

        //No usuario no puede ser repetido
        $usuario = User::where('usuario', $request->usuario)->first();
        //dd($usuario);


        if ($usuario) {
            flash('El usuario ya existe', 'error');
            return redirect()->back();
        }

        //Calcular la edad
        $fechaNacimiento = $request->fechaNacimiento;
        $edad = date_diff(date_create($fechaNacimiento), date_create('now'))->y;


        //dd($fechaNacimiento);
        //dd($edad);

        //dd($fechaNacimiento);

        try {

            $user = new User();
            $user->nombre = $request->nombre;
            $user->apellido = $request->apellido;
            $user->genero = $request->genero;
            $user->usuario = $request->usuario;
            $user->rol = $request->rol;
            $user->fechaNacimiento = $fechaNacimiento;
            $user->DUI = $request->DUI;
            $user->edad = $edad;
            $user->password = Hash::make("0000");
            $user->estado = 1;

            $user->save();

            flash('Usuario creado correctamente', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            flash('Error al guardar el usuario', 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->checkRole();

        //dd($request->all());

        $user = User::find($request->id);

        $request->validate(
            [
                'nombre' => 'required',
                'apellido' => 'required',
                'genero' => 'required',
                'usuario' => 'required',
                'rol' => 'required',
            ],
            [
                'nombre.required' => 'El campo nombre es requerido',
                'apellido.required' => 'El campo apellido es requerido',
                'genero.required' => 'El campo genero es requerido',
                'usuario.required' => 'El campo usuario es requerido',
                'rol.required' => 'El campo rol es requerido',
            ]
        );


        $edad = date_diff(date_create($request->fechaNacimiento), date_create('now'))->y;


        //Actualizar los datos
        try {

            $user->nombre = $request->nombre;
            $user->apellido = $request->apellido;
            $user->genero = $request->genero;
            $user->usuario = $request->usuario;
            $user->rol = $request->rol;
            $user->fechaNacimiento = $request->fechaNacimiento;
            $user->DUI = $request->DUI;
            $user->edad = $edad;
            $user->estado = $request->estado;

            $user->save();

            flash('Usuario actualizado correctamente', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            flash('Error al actualizar el usuario', 'error');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $this->checkRole();

        $user = User::find($id);

        //Verificar que el usuario no sea el mismo que esta autenticado
        if ($user->id == auth()->user()->id) {
            //flash('No puedes eliminar tu propio usuario', 'error');
            return response()->json(['error' => 'No puedes eliminar tu propio usuario']);
        }

        //Verificar que si elimina un administrador, queden al menos 2
        if ($user->rol == 'admin') {
            $admins = User::where('rol', 'is', 'admin')->count();

            if ($admins == 2) {
                //flash('Debe de haber al menos dos administradores', 'error');
                return response()->json(['error' => 'Debe de haber al menos dos administradores']);
            }
        }

        try {
            $user->delete();
            //flash('Usuario eliminado correctamente', 'success');
            return response()->json(['success' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            //flash('Error al eliminar el usuario', 'error');
            return response()->json(['error' => 'Error al eliminar el usuario']);
        }
    }

    public function resetPassword($id)
    {
        $this->checkRole();

        $user = User::find($id);

        try {
            $user->password = Hash::make("0000");
            $user->save();
            //flash('Contraseña restaurada correctamente', 'success');
            return response()->json(['success' => 'Contraseña restaurada correctamente']);
        } catch (\Exception $e) {
            //flash('Error al restaurar la contraseña', 'error');
            return response()->json(['error' => 'Error al restaurar la contraseña']);
        }
    }
}
