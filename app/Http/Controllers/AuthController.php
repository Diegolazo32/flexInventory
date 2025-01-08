<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

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

                try {

                    if($usuario->estado == 0){
                        flash('Usuario inactivo', 'error');
                        return back();
                    }

                    if($usuario->estado == 2){
                        flash('Usuario bloqueado', 'error');
                        return back();
                    }

                    if (Hash::check('0000', $usuario->password)) {
                        Auth::login($usuario);
                        //dd('Bienvenido ' . $usuario->nombre);
                        flash('Por favor cambie su contraseña', 'warning');
                        return redirect()->route('password', ['id' => $usuario->id]);
                    }

                    if (password_verify($request->password, $usuario->password)) {
                        Auth::login($usuario);
                        flash('Bienvenido ' . $usuario->nombre, 'success');
                        return redirect()->route('dashboard');
                    } else {
                        flash('Usuario o contraseña incorrecta', 'error');
                        return back();
                    }

                } catch (\Exception $e) {
                    flash('Error al iniciar sesion', 'error');
                    return back();
                }
            }
            else
            {
                flash('Usuario o contraseña incorrecta', 'error');
                return back();
            }
        } catch (\Exception $e) {
            flash('Error al iniciar sesion', 'error');
            return back();
        }
    }

    public function logout()
    {

        //Si el usuario esta autenticado, se cierra la sesion
        try {
            if (Auth::check()) {
                Auth::logout();
                flash('Sesion cerrada', 'success');
                return redirect()->route('login');
            } else {
                //Si no esta autenticado, se redirige a la pagina de login
                flash('No hay sesion activa', 'error');
                return redirect()->route('login');
            }
        } catch (\Exception $e) {
            flash('Error al cerrar sesion', 'error');
            return back();
        }
    }

    public function password()
    {
        return view('Auth.restorePassword');
    }

    public function updatePassword($id, Request $request)
    {
        $usuario = User::find($id);

        if ($request->password == 'password') {
            flash('La contraseña no puede ser igual a password', 'error');
            return back();
        }

        $usuario->password = Hash::make($request->password);


        $usuario->save();

        Auth::logout();

        flash('Contraseña actualizada', 'success');
        return redirect()->route('login');
    }
}
