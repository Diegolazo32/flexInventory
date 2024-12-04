<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //Si el request tiene un campo de busqueda
        if ($request->has('search')) {

            if($request->search == null){
                return redirect()->route('users');
            }

            $users = User::where('nombre', 'like', "%{$request->search}%");

            if ($users->count() == 0) {
                //toastr()->error('No se encontraron resultados');
                return redirect()->back()->with('error', 'No se encontraron resultados');
            }

            $users = $users->paginate(10);

        } else {
            $users = User::paginate(10);
        }

        if($request->has('clear')){
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
            toastr()->error('El usuario ya existe');
            return redirect()->back()->with('error', 'El usuario ya existe');
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

            //toastr()->success('Usuario guardado correctamente');
            return redirect()->back()->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            toastr()->error('Error al guardar el usuario');
            return redirect()->back()->with('error', 'Error al guardar el usuario');
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

        

        //Actualizar los datos
        try {

            $user->nombre = $request->nombre;
            $user->apellido = $request->apellido;
            $user->genero = $request->genero;
            $user->usuario = $request->usuario;
            $user->rol = $request->rol;
            $user->fechaNacimiento = $request->fechaNacimiento;
            $user->DUI = $request->DUI;
            $user->edad = $request->edad;
            $user->estado = $request->estado;

            $user->save();

            toastr()->success('Usuario actualizado correctamente');
            return redirect()->back()->with('success', 'Usuario actualizado correctamente');
        } catch (\Exception $e) {
            toastr()->error('Error al guardar el usuario');
            return redirect()->back()->with('error', 'Error al guardar el usuario');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
