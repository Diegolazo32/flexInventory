<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\productos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class reportesController extends Controller
{
    private $rolPermisoController;

    public function productosIndex()
    {
        /*$this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(73);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }*/

        return view('reportes.productos');
    }

    public function productosGenerar(Request $request)
    {

        $productos = productos::where('estado', $request->estado)->get();

        $pdf = PDF::loadView('reportes.pdfReportes.reporteProductos', compact('productos'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isPhpEnabled' => true]);

        return $pdf->stream('reporteProductos.pdf');
    }
}
