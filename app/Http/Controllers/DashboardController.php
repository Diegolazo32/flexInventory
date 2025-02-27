<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\clientes;
use App\Models\inventario;
use App\Models\productos;
use App\Models\proveedores;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        if (Auth::user()->estado == 8 || Auth::user()->estado == 2) {
            Auth::logout();

            flash('Usuario bloqueado o sin credenciales', 'error');
            return redirect()->route('login');
        }



        $auditoriaController = new AuditoriaController();

        $clientes = clientes::all();
        $productos = productos::all();
        $categorias = categoria::all();
        $proveedores = proveedores::all();
        $Activo = inventario::where('estado', 3)->orderBy('fechaApertura', 'desc')->first();


        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Ingreso al dashboard', 'Dashboard', '-', '-');
        return view('Dashboard.dashboard')->with([
            'clientes' => $clientes,
            'productos' => $productos,
            'categorias' => $categorias,
            'proveedores' => $proveedores,
            'Activo' => $Activo
        ]);
    }
}
