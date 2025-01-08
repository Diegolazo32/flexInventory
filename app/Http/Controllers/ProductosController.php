<?php

namespace App\Http\Controllers;

use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductosController extends Controller
{
    private $rolPermisoController;

    public function index()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(32);

        if (!$permiso) {
            flash('No tiene permisos para acceder a esta sección', 'error');
            return redirect()->route('dashboard');
        }

        return view('productos.index');
    }

    public function getAllProductos()
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(36);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        $productos = productos::all();
        return response()->json($productos);
    }

    public function store(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(33);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
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
            $producto = new productos();
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->precioCompra = $request->precioCompra;
            $producto->precioVenta = $request->precioVenta;
            $producto->precioDescuento = $request->precioDescuento;
            $producto->precioEspecial = $request->precioEspecial;
            $producto->fechaVencimiento = $request->fechaVencimiento;
            $producto->stock = $request->stock;
            $producto->stockInicial = $request->stockInicial;
            $producto->stockMinimo = $request->stockMinimo;
            $producto->stockMaximo = $request->stockMaximo;
            $producto->categoria = $request->categoria;
            $producto->tipoVenta = $request->tipoVenta;
            $producto->proveedor = $request->proveedor;
            $producto->unidad = $request->unidad;
            $producto->estado = 1;
            $producto->save();
            return response()->json(['success' => 'Producto guardado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar el producto']);
        }
    }

    public function update(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(34);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
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
            $producto->fechaVencimiento = $request->fechaVencimiento;
            $producto->stock = $request->stock;
            $producto->stockInicial = $request->stockInicial;
            $producto->stockMinimo = $request->stockMinimo;
            $producto->stockMaximo = $request->stockMaximo;
            $producto->categoria = $request->categoria;
            $producto->tipoVenta = $request->tipoVenta;
            $producto->proveedor = $request->proveedor;
            $producto->unidad = $request->unidad;
            $producto->estado = 1;
            $producto->save();
            return response()->json(['success' => 'Producto actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto']);
        }
    }

    public function delete(Request $request)
    {
        $this->rolPermisoController = new RolPermisoController();
        $permiso = $this->rolPermisoController->checkPermisos(35);

        if (!$permiso) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción']);
        }

        try {
            $producto = productos::find($request->id);
            $producto->delete();

            return response()->json(['success' => 'Producto eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el producto']);
        }
    }
}
