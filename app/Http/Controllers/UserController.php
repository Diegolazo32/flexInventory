<?php

namespace App\Http\Controllers;

use App\Models\rolPermiso;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


use function Laravel\Prompts\error;

class UserController extends Controller
{

    //Instancia de rolpermisoController
    private $rolPermisoController;

    public function getAllUsers(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(9);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        if ($request->search) {

            $users = User::where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('apellido', 'like', '%' . $request->search . '%')
                ->orWhere('usuario', 'like', '%' . $request->search . '%')
                ->orWhere('rol', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);

            foreach ($users as $user) {

                if ($user->password == Hash::check('0000', $user->password)) {
                    $user->hasPassword = false;
                } else {
                    $user->hasPassword = true;
                }
            }

            return response()->json($users);
        }

        if ($request->onlyActive) {

            $users = User::where('estado', 1)->get();

            foreach ($users as $user) {

                if ($user->password == Hash::check('0000', $user->password)) {
                    $user->hasPassword = false;
                } else {
                    $user->hasPassword = true;
                }
            }

            return response()->json($users);
        }

        if ($request->per_page) {

            $users = User::paginate($request->per_page);

            foreach ($users as $user) {

                if ($user->password == Hash::check('0000', $user->password)) {
                    $user->hasPassword = false;
                } else {
                    $user->hasPassword = true;
                }
            }

            return response()->json($users);
        }

        $users = User::all();

        foreach ($users as $user) {

            if ($user->password == Hash::check('0000', $user->password)) {
                $user->hasPassword = false;
            } else {
                $user->hasPassword = true;
            }
        }

        return response()->json($users);
    }

    public function index(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(5);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceso a pantalla de usuarios sin permiso', 'Usuarios', '-', '-');

            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }


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

        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a la pantalla de usuarios', 'Usuarios', '-', '-');

        return view('Users.index')->with([
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(6);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intenno de crear usuario sin permiss', 'Usuarios', '-', '-');

            return response()->json(['error' => 'No tienes permisos para crear un usuario']);
        }

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

        if ($usuario) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al crear usuario - Usuario repetido', 'Usuarios', '-', '-');

            return response()->json(['error' => 'El usuario ya existe']);
        }

        //Calcular la edad
        $fechaNacimiento = $request->fechaNacimiento;
        $edad = date_diff(date_create($fechaNacimiento), date_create('now'))->y;

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

            $auditoria->registrarEvento(Auth::user()->nombre, 'Crear usuario', 'Usuarios', '-', $user);

            return response()->json(['success' => 'Usuario guardado correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al crear usuario', 'Usuarios', '-', '-');

            return response()->json(['error' => 'Error al guardar el usuario']);
        }
    }

    public function update(Request $request, User $user)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(7);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de actualizar usuario sin permiso', 'Usuarios', '-', '-');

            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        $oldUser = User::find($request->id);
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

            $auditoria->registrarEvento(Auth::user()->nombre, 'Actualizacion de usuario', 'Usuarios', $oldUser, $user);

            return response()->json(['success' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al actualizar usuario', 'Usuarios', '-', '-');
            return response()->json(['error' => 'Error al actualizar el usuario']);
        }
    }

    public function delete($id)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(8);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de eliminar usuario sin permiso', 'Usuarios', '-', '-');

            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        $user = User::find($id);

        //Verificar que el usuario no sea el mismo que esta autenticado
        if ($user->id == auth()->user()->id) {
            //flash('No puedes eliminar tu propio usuario', 'error');
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de eliminar su mismo usuario', 'Usuarios', '-', '-');
            return response()->json(['error' => 'No puedes eliminar tu propio usuario']);
        }

        //Verificar que si elimina un administrador, queden al menos 2
        if ($user->rol == 'admin') {
            $admins = User::where('rol', 'is', 'admin')->count();

            if ($admins == 2) {
                //flash('Debe de haber al menos dos administradores', 'error');
                $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de eliminar administrador sin dejar al menos dos en sistema', 'Usuarios', '-', '-');
                return response()->json(['error' => 'Debe de haber al menos dos administradores']);
            }
        }

        try {
            $user->delete();
            //flash('Usuario eliminado correctamente', 'success');
            $auditoria->registrarEvento(Auth::user()->nombre, 'Usuario eliminado', 'Usuarios', $user, '-');

            return response()->json(['success' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            //flash('Error al eliminar el usuario', 'error');
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error al eliminar usuario', 'Usuarios', $user, '-');

            return response()->json(['error' => 'Error al eliminar el usuario']);
        }
    }

    public function resetPassword($id)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(10);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de reestablecer password de usuario sin permiso', 'Usuarios', '-', '-');

            return response()->json(['error' => 'No tiene permisos para acceder a esta sección']);
        }

        $user = User::find($id);

        try {
            $user->password = Hash::make("0000");
            $user->estado = 8;
            $user->save();
            $auditoria->registrarEvento(Auth::user()->nombre, 'Reestablecer passsword de usuario', 'Usuarios', '-', $user);


            if ($user->id == auth()->user()->id) {
                Auth::logout();
                flash('Contraseña restaurada correctamente', 'success');
                return redirect()->route('login');
            }

            //flash('Contraseña restaurada correctamente', 'success');
            return response()->json(['success' => 'Contraseña restaurada correctamente']);
        } catch (\Exception $e) {
            //flash('Error al restaurar la contraseña', 'error');
            $auditoria->registrarEvento(Auth::user()->nombre, 'Error  al reestablecer password', 'Usuarios', '-', $user);

            return response()->json(['error' => 'Error al restaurar la contraseña']);
        }
    }

    public function ajustes()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(11);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceder a ajustes sin permiso', 'Usuarios', '-', '-');

            flash('No tienes permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        $auditoria->registrarEvento(Auth::user()->nombre, 'Acceso a ajustes', 'Usuarios', '-', '-');

        return view('Users.ajustes');
    }

}
