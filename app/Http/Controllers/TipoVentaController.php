<?php

namespace App\Http\Controllers;

use App\Models\tipoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoVentaController extends Controller
{
    public function checkRole()
    {
        try {
            if (Auth::user()->rol != 1) {
                flash('No tienes permisos para acceder a esta sección', 'error');
                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar el rol']);
        }
    }

    public function getAllTipoVenta()
    {
        $this->checkRole();
        $tipoventa = tipoVenta::all();
        return response()->json($tipoventa);
    }

    public function index()
    {
        $this->checkRole();
        return view('tipoventa.index');
    }
}
