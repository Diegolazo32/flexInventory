<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

});
