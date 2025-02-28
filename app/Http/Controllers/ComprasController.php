<?php

namespace App\Http\Controllers;

use App\Models\compraProductos;
use App\Models\compras;
use App\Models\inventario;
use App\Models\kardex;
use App\Models\lotes;
use App\Models\productos;
use App\Models\proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComprasController extends Controller
{

    private $rolPermisoController;
    private $inventarioActivo;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(64);
        $auditoriaController = new AuditoriaController();

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de acceso sin permiso', 'compras', '-', '-');
            return redirect()->route('dashboard');
        }

        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Acceso a pantalla de compras', 'compras', '-', '-');
        return view('compras.index');
    }

    public function getAllCompras(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(68);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        if (compras::all()->count() == 0) {
            return response()->json(['error' => 'No hay compras registradas']);
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

    public function getCompraDetails($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(69);
        $auditoriaController = new AuditoriaController();


        if (!$permiso) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de ver detalles de compra sin permiso', 'compras', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $compra = compras::find($id);

        if (!$compra) {
            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de ver detalles de compra inexistente', 'compras', '-', '-');
            return response()->json(['error' => 'Compra no encontrada']);
        }

        $compraProductos = compraProductos::where('compra', $compra->id)->get();

        $productos = [];

        foreach ($compraProductos as $compraProducto) {
            $producto = productos::find($compraProducto->producto);
            $proveedor = proveedores::find($compraProducto->proveedor);

            $productos[] = [
                'id' => $producto->id,
                'codigo' => $producto->codigo,
                'nombre' => $producto->nombre,
                'proveedor' => $proveedor->nombre,
                'cantidad' => $compraProducto->cantidad,
                'precio' => $compraProducto->precioCompra,
                'total' => $compraProducto->totalCompra
            ];
        }

        $auditoriaController->registrarEvento(Auth::user()->nombre, 'Acceso a detalles de compra', 'compras', '-', $compra);
        return response()->json(['compra' => $compra, 'productos' => $productos]);
    }

    public function store(Request $request)
    {

        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(65);
        $auditoriaController = new AuditoriaController();


        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear sin permiso', 'compras', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $request->validate(
            [
                'codigo' => 'required',
                'fecha' => 'required',
                'total' => 'required',
                'productos' => 'required'
            ],
            [
                'codigo.required' => 'El código es requerido',
                'fecha.required' => 'La fecha es requerida',
                'total.required' => 'El total es requerido',
                'productos.required' => 'Los productos son requeridos'
            ]
        );

        try {

            $activo = inventario::where('estado', 3)->first();

            if (!$activo) {

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear compra sin inventario activo', 'compras', '-', '-');
                return response()->json(['error' => 'No hay un inventario activo']);
            }



            //Crear compra
            $compra = new compras();
            $compra->codigo = $request->codigo;
            //Fecha de hoy
            $compra->fecha = $request->fecha;
            $compra->total = $request->total;
            $compra->estado = $request->estado;
            $compra->tipoPago = $request->tipoPago;
            $compra->observaciones = $request->observaciones;
            $compra->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de compra', 'compras', '-', $compra);
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

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de detalle de compra', 'compras', '-', $compraProducto);

                if ($request->estado == 6) {

                    //Obetener detalles del producto
                    $product = productos::find($producto['id']);
                    //Obtener el ultimo lote activo del producto
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

                    $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de lote', 'lotes', '-', $lote);

                    //Actualizar stock
                    //Obtener todos los lotes activos del producto
                    $lotes = lotes::where('producto', $product->id)->get();

                    $stockTotal = 0;
                    foreach ($lotes as $lote) {
                        $stockTotal += $lote->cantidad;
                    }

                    $product->stock = $stockTotal;
                    $product->precioCompra = $producto['precio'];
                    $product->proveedor = $producto['proveedor'];

                    if ($product->stock > 0) {
                        $product->estado = 1;
                    }

                    $product->save();

                    $auditoriaController->registrarEvento(Auth::user()->nombre, 'Actualización de producto', 'productos', '-', $product);

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
                    $kardex->observacion = 'Compra de producto';
                    $kardex->save();


                    $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de movimiento de kardex', 'kardex', '-', $kardex);
                }

            }

            return response()->json(['success' => 'Compra realizada con éxito']);
        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al crear compra', 'compras', '-', '-');
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    //Aprobar compra
    public function payCompra($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(66);
        $auditoriaController = new AuditoriaController();


        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear sin permiso', 'compras', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $activo = inventario::where('estado', 3)->first();

        if (!$activo) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear compra sin inventario activo', 'compras', '-', '-');
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        try {
            $compra = compras::find($id);

            if (!$compra) {

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de pagar compra inexistente', 'compras', '-', '-');
                return response()->json(['error' => 'Compra no encontrada']);
            }

            $compraProductos = compraProductos::where('compra', $compra->id)->get();

            if ($compraProductos->count() == 0) {

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de pagar compra sin productos', 'compras', '-', '-');
                return response()->json(['error' => 'La compra no tiene productos']);
            }

            foreach ($compraProductos as $compraProducto) {

                //Obetener detalles del producto
                $product = productos::find($compraProducto->producto);
                //Obtener el ultimo lote del producto
                $loteProducto = lotes::where('producto', $product['id'])->orderBy('id', 'desc')->first();
                //Crear lote
                $lote = new lotes();
                //Dia mes año hora minuto segundo
                $lote->codigo = 'L' . $product->codigo . $product->id . '-' . date('Y-m-d') . date('H:i:s');
                $lote->numero = $loteProducto->numero + 1;
                $lote->cantidad = $compraProducto->cantidad;
                $lote->fechaVencimiento = $compraProducto->fechaVencimiento;
                $lote->producto = $compraProducto->producto;
                $lote->estado = 1;
                $lote->inventario = $activo->id;
                $lote->save();

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de lote', 'lotes', '-', $lote);

                //Actualizar stock
                //Obtener todos los lotes del producto
                $lotes = lotes::where('producto', $product->id)->get();

                $stockTotal = 0;
                foreach ($lotes as $lote) {
                    $stockTotal += $lote->cantidad;
                }

                $product->stock = $stockTotal;
                $product->precioCompra = $compraProducto->precioCompra;
                $product->proveedor = $compraProducto->proveedor;

                if ($product->stock > 0) {
                    $product->estado = 1;
                }

                $product->save();

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Actualización de producto', 'productos', '-', $product);

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
                $kardex->cantidad = $compraProducto->cantidad;
                $kardex->accion = 1;
                $kardex->inventario = $activo->id;
                $kardex->observacion = 'Aprobacion de compra de producto';
                $kardex->save();

                $auditoriaController->registrarEvento(Auth::user()->nombre, 'Creación de movimiento de kardex', 'kardex', '-', $kardex);

            }

            $compra->estado = 6;
            $compra->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Compra pagada', 'compras', '-', $compra);
            return response()->json(['success' => 'Compra pagada con éxito']);

        } catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al pagar compra', 'compras', '-', '-');
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    //Anular compra
    public function nullifyCompra($id)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(67);
        $auditoriaController = new AuditoriaController();


        if (!$permiso) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear sin permiso', 'compras', '-', '-');
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $activo = inventario::where('estado', 3)->first();

        if (!$activo) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Intento de crear compra sin inventario activo', 'compras', '-', '-');
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        try
        {

            $compra = compras::find($id);
            $compra->estado = 7;
            $compra->save();

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Compra anulada', 'compras', '-', $compra);

            return response()->json(['success' => 'Compra anulada con éxito']);

        }
        catch (\Exception $e) {

            $auditoriaController->registrarEvento(Auth::user()->nombre, 'Error al anular compra', 'compras', '-', '-');
            return response()->json(['error' => $e->getMessage()]);
        }


    }
}
