<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RolPermisoController;
use App\Http\Controllers\TipoVentaController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});



//Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('AuthLogin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {

    //Acess Denied
    Route::get('/denied', function () {
        return view('layouts.accessDenied');
    })->name('denied');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Cambiar contraseña
    Route::get('/auth/password/{id}', [AuthController::class, 'password'])->name('password');
    Route::post('/auth/password/{id}', [AuthController::class, 'updatePassword'])->name('updatePassword');

    //Usuarios
    Route::controller(UserController::class)->group(function () {
        Route::get('/allUsers', [UserController::class, 'getAllUsers'])->name('allUsers');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/edit/{id}', [UserController::class, 'update'])->name('users.edit');
        Route::post('/users/rstpsw/{id}', [UserController::class, 'resetPassword'])->name('users.restore');
        Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    });

    //Unidades
    Route::controller(UnidadesController::class)->group(function () {
        Route::get('/allUnidades', [UnidadesController::class, 'getAllUnidades'])->name('allUnidades');
        Route::get('/unidades', [UnidadesController::class, 'index'])->name('unidades');
        Route::post('/unidades/store', [UnidadesController::class, 'store'])->name('unidades.store');
        Route::post('/unidades/edit/{id}', [UnidadesController::class, 'update'])->name('unidades.edit');
        Route::delete('/unidades/delete/{id}', [UnidadesController::class, 'delete'])->name('unidades.delete');
    });

    //Estados
    Route::controller(EstadosController::class)->group(function () {
        Route::get('/allEstados', [EstadosController::class, 'getAllEstados'])->name('allEstados');
        Route::get('/estados', [EstadosController::class, 'index'])->name('estados');
        Route::post('/estados/store', [EstadosController::class, 'store'])->name('estados.store');
        Route::post('/estados/edit/{id}', [EstadosController::class, 'update'])->name('estados.edit');
        Route::delete('/estados/delete/{id}', [EstadosController::class, 'delete'])->name('estados.delete');
    });

    //Categorias
    Route::controller(CategoriaController::class)->group(function () {
        Route::get('/allCategorias', [CategoriaController::class, 'getAllCategorias'])->name('allCategorias');
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
        Route::post('/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::post('/categorias/edit/{id}', [CategoriaController::class, 'update'])->name('categorias.edit');
        Route::delete('/categorias/delete/{id}', [CategoriaController::class, 'delete'])->name('categorias.delete');
    });

    //Proveedores
    Route::controller(ProveedoresController::class)->group(function () {
        Route::get('/allProveedores', [ProveedoresController::class, 'getAllProveedores'])->name('allProveedores');
        Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('proveedores');
        Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedores.store');
        Route::post('/proveedores/edit/{id}', [ProveedoresController::class, 'update'])->name('proveedores.edit');
        Route::delete('/proveedores/delete/{id}', [ProveedoresController::class, 'delete'])->name('proveedores.delete');
    });

    //Clientes
    Route::controller(ClientesController::class)->group(function () {
        Route::get('/allClientes', [ClientesController::class, 'getAllClientes'])->name('allClientes');
        Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes');
        Route::post('/clientes/store', [ClientesController::class, 'store'])->name('clientes.store');
        Route::post('/clientes/edit/{id}', [ClientesController::class, 'update'])->name('clientes.edit');
        Route::delete('/clientes/delete/{id}', [ClientesController::class, 'delete'])->name('clientes.delete');
    });

    //TipoVenta
    Route::controller(TipoVentaController::class)->group(function () {
        Route::get('/allTipoVenta', [TipoVentaController::class, 'getAllTipoVenta'])->name('allTipoVenta');
        Route::get('/tipoVenta', [TipoVentaController::class, 'index'])->name('tipoVenta');
        Route::post('/tipoVenta/store', [TipoVentaController::class, 'store'])->name('tipoVenta.store');
        Route::post('/tipoVenta/edit/{id}', [TipoVentaController::class, 'update'])->name('tipoVenta.edit');
        Route::delete('/tipoVenta/delete/{id}', [TipoVentaController::class, 'delete'])->name('tipoVenta.delete');
    });

    //Productos
    Route::controller(ProductosController::class)->group(function () {
        Route::get('/allProductos', [ProductosController::class, 'getAllProductos'])->name('allProductos');
        Route::get('/productos', [ProductosController::class, 'index'])->name('productos');
        Route::post('/productos/store', [ProductosController::class, 'store'])->name('productos.store');
        Route::post('/productos/edit/{id}', [ProductosController::class, 'update'])->name('productos.edit');
        Route::delete('/productos/delete/{id}', [ProductosController::class, 'delete'])->name('productos.delete');
    });

    //Roles
    Route::controller(RolesController::class)->group(function () {
        Route::get('/allRoles', [RolesController::class, 'getAllRoles'])->name('allRoles');
        Route::get('/roles', [RolesController::class, 'index'])->name('roles');
        Route::post('/roles/store', [RolesController::class, 'store'])->name('roles.store');
        Route::post('/roles/edit/{id}', [RolesController::class, 'update'])->name('roles.edit');
        Route::delete('/roles/delete/{id}', [RolesController::class, 'delete'])->name('roles.delete');
        Route::post('/roles/permisos/{id}', [RolesController::class, 'permisos'])->name('roles.permisos');
    });

    //Permisos
    Route::controller(PermisosController::class)->group(function () {
        Route::get('/allPermisos', [PermisosController::class, 'getAllPermisos'])->name('allPermisos');
        Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos');
        Route::post('/permisos/store', [PermisosController::class, 'store'])->name('permisos.store');
        Route::post('/permisos/edit/{id}', [PermisosController::class, 'update'])->name('permisos.edit');
        Route::delete('/permisos/delete/{id}', [PermisosController::class, 'delete'])->name('permisos.delete');
    });

    //Grupos
    Route::get('/allGrupos', [GruposController::class, 'getAllGrupos'])->name('allGrupos');

    //Permisos por Rol
    Route::controller(RolPermisoController::class)->group(function () {
        Route::get('/allRolPermiso', [RolPermisoController::class, 'getAllRolPermiso'])->name('allRolPermiso');
        Route::get('/permisosByRol/{id}', [RolPermisoController::class, 'getPermisosByRol'])->name('permisosByRol');
    });
});
