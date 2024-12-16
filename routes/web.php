<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\ProveedoresController;
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

Route::get('/', function () {
    return redirect()->route('login');
});


Route::controller(AuthController::class)->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('AuthLogin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/auth/password/{id}', [AuthController::class, 'password'])->name('password');
    Route::post('/auth/password/{id}', [AuthController::class, 'updatePassword'])->name('updatePassword');

    Route::controller(UserController::class)->group(function () {
        Route::get('/allUsers', [UserController::class, 'getAllUsers'])->name('allUsers');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/edit/{id}', [UserController::class, 'update'])->name('users.edit');
        Route::post('/users/rstpsw/{id}', [UserController::class, 'resetPassword'])->name('users.restore');
        Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    });

    Route::controller(UnidadesController::class)->group(function () {
        Route::get('/allUnidades', [UnidadesController::class, 'getAllUnidades'])->name('allUnidades');
        Route::get('/unidades', [UnidadesController::class, 'index'])->name('unidades');
        Route::post('/unidades/store', [UnidadesController::class, 'store'])->name('unidades.store');
        Route::post('/unidades/edit/{id}', [UnidadesController::class, 'update'])->name('unidades.edit');
        Route::delete('/unidades/delete/{id}', [UnidadesController::class, 'delete'])->name('unidades.delete');
    });

    Route::controller(EstadosController::class)->group(function () {
        Route::get('/allEstados', [EstadosController::class, 'getAllEstados'])->name('allEstados');
        Route::get('/estados', [EstadosController::class, 'index'])->name('estados');
        Route::post('/estados/store', [EstadosController::class, 'store'])->name('estados.store');
        Route::post('/estados/edit/{id}', [EstadosController::class, 'update'])->name('estados.edit');
        Route::delete('/estados/delete/{id}', [EstadosController::class, 'delete'])->name('estados.delete');
    });

    Route::controller(CategoriaController::class)->group(function () {
        Route::get('/allCategorias', [CategoriaController::class, 'getAllCategorias'])->name('allCategorias');
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
        Route::post('/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::post('/categorias/edit/{id}', [CategoriaController::class, 'update'])->name('categorias.edit');
        Route::delete('/categorias/delete/{id}', [CategoriaController::class, 'delete'])->name('categorias.delete');
    });

    Route::controller(ProveedoresController::class)->group(function () {
        Route::get('/allProveedores', [ProveedoresController::class, 'getAllProveedores'])->name('allProveedores');
        Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('proveedores');
        Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedores.store');
        Route::post('/proveedores/edit/{id}', [ProveedoresController::class, 'update'])->name('proveedores.edit');
        Route::delete('/proveedores/delete/{id}', [ProveedoresController::class, 'delete'])->name('proveedores.delete');
    });
});
