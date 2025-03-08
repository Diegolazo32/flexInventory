<?php

namespace App\Http\Controllers;

use App\Models\resolucionTickets;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    private $rolPermisoController;
    private $inventarioActivo;

    public function menuCajeros()
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59);


        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect('dashboard');
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            flash('No hay inventario activo', 'error');
            return redirect('dashboard');
        }

        $resolucionActiva = resolucionTickets::where('estado', 1)->first();
        if (!$resolucionActiva) {
            flash('No hay resolución activa', 'error');
            return redirect('tickets');
        }

        return view('ventas.menuCajero');
    }
}
