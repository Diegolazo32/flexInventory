<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class AuthController extends Controller
{

    private $auditoriaController;


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

                    if ($usuario->estado == 2) {
                        flash('Usuario bloqueado', 'error');
                        return back();
                    }

                    if (password_verify($request->password, $usuario->password)) {
                        Auth::login($usuario);
                        $auditoriaController = new AuditoriaController();
                        $auditoriaController->registrarEvento($usuario->usuario, 'Inicio de sesion', 'usuarios', '-', '-');

                        if ($usuario->estado == 8) {
                            flash('Por favor cambie su contraseña', 'warning');
                            $auditoriaController = new AuditoriaController();
                            $auditoriaController->registrarEvento($usuario->usuario, 'Cambio de contraseña', 'usuarios', '-', '-');
                            return redirect()->route('password', $usuario->id);
                        }

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
            } else {
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
                $auditoriaController = new AuditoriaController();
                $auditoriaController->registrarEvento('Sistema', 'Cierre de sesion', 'usuarios', '-', '-');
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

    public function password($id)
    {

        if(Auth::user()->id != $id){
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        if(Auth::user()->estado == 2){
            flash('Usuario bloqueado', 'error');
            return redirect()->route('login');
        }

        if(Auth::user()->estado == 1){
            flash('No tiene permiso para acceder a esta seccion', 'error');
            return redirect()->route('dashboard');
        }

        $usuario = User::find($id);
        $auditoriaController = new AuditoriaController();
        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Solicitud de cambio de contraseña', 'usuarios', '-', '-');
        return view('Auth.restorePassword', compact('usuario'));
    }

    public function updatePassword($id, Request $request)
    {


        $usuario = User::find($id);

        if ($usuario == null) {
            flash('Usuario no encontrado', 'error');
            return back();
        }

        if ($usuario->estado == 2) {
            flash('Usuario bloqueado', 'error');
            return back();
        }

        if ($request->password == 'password') {
            flash('Contraseña no permitida', 'error');
            return back();
        }

        $usuario->password = Hash::make($request->password);
        $usuario->estado = 1;
        $usuario->save();
        $auditoriaController = new AuditoriaController();
        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Cambio de contraseña', 'usuarios', '-', '-');

        Auth::logout();

        flash('Contraseña actualizada', 'success');
        return redirect()->route('login');
    }
}
