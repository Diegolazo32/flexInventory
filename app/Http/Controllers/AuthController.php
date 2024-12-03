<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'usuario' => 'required',
                'password' => 'required'
            ],
            [
                'usuario.required' => 'El campo usuario es requerido',
                'password.required' => 'El campo contraseña es requerido'
            ]
        );

        try {

            $usuario = User::where('usuario', $request->usuario)->first();

            if ($usuario) {
                if (password_verify($request->password, $usuario->password)) {
                    Auth::login($usuario);
                    toastr()->success('Bienvenido ' . $usuario->nombre);
                    return redirect()->route('dashboard');
                }
            }
        } catch (\Exception $e) {
            toastr()->error('Error al iniciar sesion');
            return back();
        }
    }

    public function logout()
    {

        //Si el usuario esta autenticado, se cierra la sesion
        try {
            if (Auth::check()) {
                Auth::logout();
                toastr()->success('Sesion cerrada correctamente');
                return redirect()->route('login');
            } else {
                //Si no esta autenticado, se redirige a la pagina de login
                toastr()->error('No hay ninguna sesion activa');
                return redirect()->route('login');
            }
        } catch (\Exception $e) {
            toastr()->error('Error al cerrar sesion');
            return back();
        }
    }
}
