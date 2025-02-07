<?php

namespace App\Http\Controllers;

use App\Models\compraProductos;
use App\Models\compras;
use App\Models\inventario;
use App\Models\kardex;
use App\Models\lotes;
use App\Models\productos;
use Illuminate\Http\Request;

class ComprasController extends Controller
{

    private $rolPermisoController;
    private $lotesController;

    private $inventarioActivo;

    public function index()
    {
        return view('compras.index');
    }

    public function getAllCompras(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(36);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        //Si el request trae un search, se filtra la busqueda
        if ($request->search) {
            $compras = compras::where('codigo', 'like', '%' . $request->search . '%')
                ->paginate($request->per_page);
            return response()->json($compras);
        }

        //Si trae un per_page, se paginan los resultados
        if ($request->per_page) {
            $compras = compras::paginate($request->per_page);
        } else {
            $compras = compras::all();
        }

        /*if ($request->onlyActive) {
            //Filtra los productos activos
            $compras = compras::where('estado', 1)->paginate($request->per_page);
        }*/

        return response()->json($compras);
    }

    public function store(Request $request)
    {


        try {

            //dd($request->all());

            //Obtener inventario activo

            $activo = inventario::where('estado', 3)->first();

            if (!$activo) {
                return response()->json(['error' => 'No hay un inventario activo']);
            }

            //Crear compra
            $compra = new compras();
            $compra->codigo = $request->codigo;
            //Fecha de hoy
            $compra->fecha = $request->fecha;
            $compra->total = $request->total;
            $compra->save();

            $productos = $request->productos;

            foreach ($productos as $producto) {

                //crear detalle de compra
                $compraProducto = new compraProductos();
                $compraProducto->compra = $compra->id;
                $compraProducto->producto = $producto['id'];
                $compraProducto->cantidad = $producto['cantidad'];
                $compraProducto->proveedor = $producto['proveedor'];
                $compraProducto->precioCompra = $producto['precio'];
                $compraProducto->totalCompra = $producto['cantidad'] * $producto['precio'];
                $compraProducto->inventario = $activo->id;
                $compraProducto->save();

                //Obetener detalles del producto
                $product = productos::find($producto['id']);

                //Obtener el ultimo lote del producto
                $loteProducto = lotes::where('producto', $product['id'])->orderBy('id', 'desc')->first();

                //Crear lote
                $lote = new lotes();
                //Dia mes año hora minuto segundo
                $lote->codigo = 'L' . $product->codigo . $product->id . '-' . date('Y-m-d') . date('H:i:s');
                $lote->numero = $loteProducto->numero + 1;
                $lote->cantidad = $producto['cantidad'];
                $lote->fechaVencimiento = $producto['fechaVencimiento'];
                $lote->producto = $producto['id'];
                $lote->estado = 1;
                $lote->inventario = $activo->id;
                $lote->save();

                //Actualizar stock
                //Obtener todos los lotes del producto
                $lotes = lotes::where('producto', $product->id)->get();

                $stockTotal = 0;
                foreach ($lotes as $lote) {
                    $stockTotal += $lote->cantidad;
                }

                $product->stock = $stockTotal;

                $product->save();

                //Actualizar fecha de vencimiento
                $lotes = lotes::where('producto', $product->id)->get();
                $fechaVencimiento = null;

                foreach ($lotes as $lote) {
                    if ($fechaVencimiento == null) {
                        $fechaVencimiento = $lote->fechaVencimiento;
                    } else {
                        if ($lote->fechaVencimiento < $fechaVencimiento) {
                            $fechaVencimiento = $lote->fechaVencimiento;
                        }
                    }
                }

                $product->fechaVencimiento = $fechaVencimiento;
                $product->save();

                //Crear movimiento de kardex
                $kardex = new kardex();
                $kardex->producto = $product->id;
                $kardex->cantidad = $producto['cantidad'];
                $kardex->accion = 1;
                $kardex->inventario = $activo->id;
                $kardex->observacion = 'Inventario inicial';
                $kardex->save();
            }

            return response()->json(['success' => 'Compra realizada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
