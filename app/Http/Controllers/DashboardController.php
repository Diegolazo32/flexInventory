<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\clientes;
use App\Models\productos;
use App\Models\proveedores;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $auditoriaController = new AuditoriaController();

        $clientes = clientes::all();
        $productos = productos::all();
        $categorias = categoria::all();
        $proveedores = proveedores::all();


        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Ingreso al dashboard', 'Dashboard', '-', '-');
        return view('Dashboard.dashboard')->with([
            'clientes' => $clientes,
            'productos' => $productos,
            'categorias' => $categorias,
            'proveedores' => $proveedores
        ]);
    }
}
