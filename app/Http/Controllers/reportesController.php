<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\categoria;
use App\Models\empresa;
use App\Models\estados;
use App\Models\productos;
use App\Models\proveedores;
use App\Models\unidades;
use Auth;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class reportesController extends Controller
{
    private $rolPermisoController;

    public function productosIndex()
    {
        /*$this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(73);
        $auditoria = new AuditoriaController();

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
                        $auditoria->registrarEvento(Auth::user()->nombre, 'Intento de acceder a pantalla de reportes sin permisos', 'Reportes', '-', '-');

            return redirect()->route('dashboard');
        }*/

        return view('reportes.productos');
    }

    public function productosGenerar(Request $request)
    {

        $auditoria = new AuditoriaController();

        $filtros = [
            'estado' => $request->estado,
            'categoria' => $request->categoria,
            'unidad' => $request->unidad,
            'proveedor' => $request->proveedor
        ];

        //Si algun campo viene como 0 se obtienen TODOS los registros
        foreach ($filtros as $key => $filtro) {
            if ($filtro == 0) {
                unset($filtros[$key]);
            }
        }

        // Obtener los datos segun los filtros de estado, categoria, unidad y/o proveedor
        $productos = productos::orderBy('nombre')->where($filtros)->get();

        $empresa = empresa::first();
        $estados = estados::all();
        $proveedores = proveedores::all();
        $categorias = categoria::all();
        $unidades = unidades::all();

        $sumaTotal = 0;
        foreach ($productos as $producto) {
            $sumaTotal += $producto->precioCompra * $producto->stock;
        }

        $stockTotal = 0;
        foreach ($productos as $producto) {
            $stockTotal += $producto->stock;
        }

        $fecha = date('d/m/Y');

        /*ParseFloat*/
        $sumaTotal = number_format($sumaTotal, 2, '.', ',');

        // Renderizar la vista en una cadena HTML
        $html = View::make('reportes.pdfReportes.reporteProductos', compact(
            'productos',
            'fecha',
            'empresa',
            'estados',
            'sumaTotal',
            'proveedores',
            'categorias',
            'unidades',
            'stockTotal'
        ))->render();

        //dd($html);

        // Configurar mPDF
        $mpdf = new Mpdf();

        $auditoria->registrarEvento(Auth::user()->nombre, 'Generacion de reporte de productos', 'Reportes', '-', '-');


        // Generar el PDF
        $mpdf->WriteHTML($html);
        return $mpdf->OutputHttpDownload('reporte_productos_' . date('Y-m-d') . '.pdf');
    }
}
