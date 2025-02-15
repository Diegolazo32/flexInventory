<?php

namespace App\Http\Controllers;

use App\Models\inventario;
use App\Models\Kardex;
use App\Models\lotes;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductosController extends Controller
{
    private $rolPermisoController;
    private $lotesController;
    private $kardexController;
    private $inventarioActivo;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(56);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('productos.index');
    }

    public function getAllProductos(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(59);

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
            $productos = productos::where('codigo', 'like', '%' . $request->search . '%')
                ->orWhere('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('categoria', 'like', '%' . $request->search . '%')
                /*  ->orWhere('tipoVenta', 'like', '%' . $request->search . '%')
                ->orWhere('proveedor', 'like', '%' . $request->search . '%')
                ->orWhere('unidad', 'like', '%' . $request->search . '%')*/
                ->paginate($request->per_page);
            return response()->json($productos);
        }

        if ($request->onlyActivePaginate) {
            //Filtra los productos activos y los pagina
            $productos = productos::where('estado', 1)->paginate($request->per_page);
            return response()->json($productos);
        }

        //Si trae un per_page, se paginan los resultados y se ordenan por $request->sortBy
        if ($request->per_page) {
            $productos = productos::orderBy($request->sortBy, 'asc')->paginate($request->per_page);
            return response()->json($productos);
        }

        if ($request->onlyActive) {
            //Filtra los productos activos sin paginar
            $productos = productos::where('estado', 1)->get();
            return response()->json($productos);
        }

        //Si no trae nada, se devuelven todos los productos
        $productos = productos::all();
        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(57);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $inventario = inventario::where('estado', 3)->first();

        $request->validate(
            [
                'codigo' => 'required',
                'nombre' => 'required',
                'precioCompra' => 'required',
                'precioVenta' => 'required',
                'stock' => 'required',
                'stockInicial' => 'required',
                'categoria' => 'required',
                'tipoVenta' => 'required',
                'proveedor' => 'required',
                'unidad' => 'required',
            ],
            [
                'codigo.required' => 'El campo código es obligatorio',
                'nombre.required' => 'El campo nombre es obligatorio',
                'precioCompra.required' => 'El campo precio de compra es obligatorio',
                'precioVenta.required' => 'El campo precio de compra es obligatorio',
                'stock.required' => 'El campo stock es obligatorio',
                'stockInicial.required' => 'El campo stock inicial es obligatorio',
                'categoria.required' => 'El campo categoría es obligatorio',
                'tipoVenta.required' => 'El campo tipo de venta es obligatorio',
                'proveedor.required' => 'El campo proveedor es obligatorio',
                'unidad.required' => 'El campo unidad es obligatorio',

            ]
        );

        try {

            $stockTotal = 0;

            //Crear producto
            $producto = new productos();
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precioCompra = $request->precioCompra;
            $producto->precioVenta = $request->precioVenta;
            $producto->precioDescuento = $request->precioDescuento;
            $producto->precioEspecial = $request->precioEspecial;
            $producto->fechaVencimiento = $request->fechaVencimiento;
            $producto->stock = 0;
            $producto->stockInicial = $request->stock;
            $producto->stockMinimo = $request->stockMinimo;
            $producto->stockMaximo = $request->stockMaximo;
            $producto->categoria = $request->categoria;
            $producto->tipoVenta = $request->tipoVenta;
            $producto->proveedor = $request->proveedor;
            $producto->unidad = $request->unidad;
            $producto->estado = 1;
            $producto->save();

            $this->lotesController = new LotesController();
            $this->lotesController->storeWithProduct($producto, $request, $inventario);

            //Actualizar stock
            $productoUpdate = productos::find($producto->id);
            $lotes = lotes::where('producto', $producto->id)->get();

            foreach ($lotes as $lote) {
                $stockTotal += $lote->cantidad;
            }

            $productoUpdate->stock = $stockTotal;

            if ($productoUpdate->stock == 0) {
                $productoUpdate->estado = 2;
            }

            $productoUpdate->save();

            return response()->json(['success' => 'Producto guardado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar el producto']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(58);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $this->inventarioActivo = new InventarioController();
        $activo = $this->inventarioActivo->checkInventarioStatus();

        if (!$activo) {
            return response()->json(['error' => 'No hay un inventario activo']);
        }

        $request->validate(
            [
                'codigo' => 'required',
                'nombre' => 'required',
                'precioCompra' => 'required',
                'precioVenta' => 'required',
                'stock' => 'required',
                'stockInicial' => 'required',
                'categoria' => 'required',
                'tipoVenta' => 'required',
                'proveedor' => 'required',
                'unidad' => 'required',
            ],
            [
                'codigo.required' => 'El campo código es obligatorio',
                'nombre.required' => 'El campo nombre es obligatorio',
                'precioCompra.required' => 'El campo precio de compra es obligatorio',
                'precioVenta.required' => 'El campo precio de compra es obligatorio',
                'stock.required' => 'El campo stock es obligatorio',
                'stockInicial.required' => 'El campo stock inicial es obligatorio',
                'categoria.required' => 'El campo categoría es obligatorio',
                'tipoVenta.required' => 'El campo tipo de venta es obligatorio',
                'proveedor.required' => 'El campo proveedor es obligatorio',
                'unidad.required' => 'El campo unidad es obligatorio',

            ]
        );

        try {
            $producto = productos::find($request->id);
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precioCompra = $request->precioCompra;
            $producto->precioVenta = $request->precioVenta;
            $producto->precioDescuento = $request->precioDescuento;
            $producto->precioEspecial = $request->precioEspecial;
            $producto->stock = $request->stock;
            $producto->stockInicial = $request->stockInicial;
            $producto->stockMinimo = $request->stockMinimo;
            $producto->stockMaximo = $request->stockMaximo;
            $producto->categoria = $request->categoria;
            $producto->tipoVenta = $request->tipoVenta;
            $producto->proveedor = $request->proveedor;
            $producto->unidad = $request->unidad;
            $producto->estado = $request->estado;
            $producto->save();
            return response()->json(['success' => 'Producto actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto']);
        }
    }

    public function lotes(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(63);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $lotes = lotes::all();
        return response()->json($lotes);
    }
}
