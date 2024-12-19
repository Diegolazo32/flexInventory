@extends('layouts.Navigation')

@section('title', 'Productos')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-10">
                        <h1>Productos</h1>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-md-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crearProductoModal" style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editProductoModalBtn"
                            data-bs-target="#editProductoModal" style="height: 40px;" hidden>
                            Editar producto
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteProductoModalBtn"
                            data-bs-target="#deleteProductoModal" style="height: 40px;" hidden>
                            Eliminar producto
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="showProductoModalBtn"
                            data-bs-target="#showProductoModal" style="height: 40px;" hidden>
                            Ver producto
                        </button>
                    </div>
                </div>
            </div>
            <!-- Buscador -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">

                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="search" placeholder="Buscar"
                                    v-model="search">
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" @click="searchFn">Buscar</button>
                                <button class="btn btn-primary" @click="cleanSearch">Limpiar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de productos -->
            <div class="row">
                <div class="card-body">
                    <div class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Precio Compra</th>
                                    <th scope="col">Precio Venta</th>
                                    <th scope="col">Precio Descuento</th>
                                    <th scope="col">Precio Especial</th>
                                    <th scope="col">Fecha Vencimiento</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">Stock Inicial</th>
                                    <th scope="col">Stock Minimo</th>
                                    <th scope="col">Stock Maximo</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Tipo Venta</th>
                                    <th scope="col">Proveedor</th>
                                    <th scope="col">Unidad</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='producto in productos' :key="producto.id">
                                    <td>@{{ producto.codigo }}</td>
                                    <td>@{{ producto.nombre }}</td>
                                    <td>$@{{ producto.precioCompra }}</td>
                                    <td>$@{{ producto.precioVenta }}</td>
                                    <td>$@{{ producto.precioDescuento ?? '-' }}</td>
                                    <td>$@{{ producto.precioEspecial ?? '-' }}</td>
                                    <td>@{{ formatDate(producto.fechaVencimiento) ?? '-' }}</td>
                                    <td>@{{ producto.stock ?? '-' }}</td>
                                    <td>@{{ producto.stockInicial ?? '-' }}</td>
                                    <td>@{{ producto.stockMinimo ?? '-' }}</td>
                                    <td>@{{ producto.stockMaximo ?? '-' }}</td>
                                    <td>@{{ categorias.find(categoria => categoria.id == producto.categoria).descripcion }} </td>
                                    <td>@{{ tipoVentas.find(tipoVenta => tipoVenta.id == producto.tipoVenta).descripcion }}</td>
                                    <td>@{{ proveedores.find(proveedor => proveedor.id == producto.proveedor).nombre }}</td>
                                    <td>@{{ unidades.find(unidad => unidad.id == producto.unidad).descripcion }}</td>
                                    <td v-if="producto.estado == 1">
                                        <span class="badge bg-success">@{{ estados.find(estado => estado.id == producto.estado).descripcion }}</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger">@{{ estados.find(estado => estado.id == producto.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editProducto(producto)">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteProducto(producto)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="17">
                                        Pendiente:
                                        Validar campos opcionales en los formularios
                                        <br>
                                        Validar campos numericos en los formularios
                                        <br>
                                        Validar campos de fecha en los formularios
                                        <br>
                                        Show modal
                                        <br>
                                        Delete
                                        <br>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="crearProductoModal" tabindex="-1" aria-labelledby="crearProductoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearProductoModalLabel">Crear producto </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('productos.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Codigo -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="codigo" name="codigo"
                                            placeholder="Codigo" @blur="validateForm" v-model="item.codigo">
                                        <label for="floatingInput">Codigo*</label>
                                        <small class="text-danger" v-if="errors.codigo">@{{ errors.codigo }}</small>
                                    </div>
                                </div>
                                <!-- Nombre -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" v-model="item.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger" v-if="errors.nombre">@{{ errors.nombre }}</small>
                                    </div>
                                </div>
                                <!-- Descripcion -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text-area" class="form-control" id="descripcion" name="descripcion"
                                            placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion">
                                        <label for="floatingInput">Descripcion</label>
                                        <small class="text-danger"
                                            v-if="errors.descripcion">@{{ errors.descripcion }}</small>
                                    </div>
                                </div>
                                <!-- Precio Compra -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioCompra" name="precioCompra"
                                            placeholder="Precio Compra" @blur="validateForm" v-model="item.precioCompra"
                                            step="0.01" min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Precio Compra*</label>
                                        <small class="text-danger"
                                            v-if="errors.precioCompra">@{{ errors.precioCompra }}</small>
                                    </div>
                                </div>
                                <!-- Precio Venta -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioVenta" name="precioVenta"
                                            placeholder="Precio Venta" @blur="validateForm" v-model="item.precioVenta"
                                            step="0.01" min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Precio Venta*</label>
                                        <small class="text-danger"
                                            v-if="errors.precioVenta">@{{ errors.precioVenta }}</small>
                                    </div>
                                </div>
                                <!-- Precio Descuento -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioDescuento"
                                            name="precioDescuento" placeholder="Precio Descuento" @blur="validateForm"
                                            v-model="item.precioDescuento" step="0.01" min="0" max="999999"
                                            maxlength="6">
                                        <label for="floatingInput">Precio Descuento</label>
                                        <small class="text-danger"
                                            v-if="errors.precioDescuento">@{{ errors.precioDescuento }}</small>
                                    </div>
                                </div>
                                <!-- Precio Especial -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioEspecial"
                                            name="precioEspecial" placeholder="Precio Especial" @blur="validateForm"
                                            v-model="item.precioEspecial" step="0.01" min="0" max="999999"
                                            maxlength="6">
                                        <label for="floatingInput">Precio Especial</label>
                                        <small class="text-danger"
                                            v-if="errors.precioEspecial">@{{ errors.precioEspecial }}</small>
                                    </div>
                                </div>
                                <!-- Fecha Vencimiento -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="fechaVencimiento"
                                            name="fechaVencimiento" placeholder="Fecha Vencimiento" @blur="validateForm"
                                            v-model="item.fechaVencimiento">
                                        <label for="floatingInput">Fecha Vencimiento</label>
                                        <small class="text-danger"
                                            v-if="errors.fechaVencimiento">@{{ errors.fechaVencimiento }}</small>
                                    </div>
                                </div>
                                <!-- Stock -->
                                <div class="form-floating col-md-2" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stock" name="stock"
                                            placeholder="Stock" @blur="validateForm" v-model="item.stock" step="1"
                                            min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Stock*</label>
                                        <small class="text-danger" v-if="errors.stock">@{{ errors.stock }}</small>
                                    </div>
                                </div>
                                <!-- Stock Inicial -->
                                <div class="form-floating col-md-2" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockInicial" name="stockInicial"
                                            placeholder="Stock Inicial" @blur="validateForm" v-model="item.stockInicial"
                                            step="1" min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Stock Inicial*</label>
                                        <small class="text-danger"
                                            v-if="errors.stockInicial">@{{ errors.stockInicial }}</small>
                                    </div>
                                </div>
                                <!-- Stock Minimo -->
                                <div class="form-floating col-md-2" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockMinimo" name="stockMinimo"
                                            placeholder="Stock Minimo" @blur="validateForm" v-model="item.stockMinimo"
                                            step="1" min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Stock Minimo</label>
                                        <small class="text-danger"
                                            v-if="errors.stockMinimo">@{{ errors.stockMinimo }}</small>
                                    </div>
                                </div>
                                <!-- Stock Maximo -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockMaximo" name="stockMaximo"
                                            placeholder="Stock Maximo" @blur="validateForm" v-model="item.stockMaximo"
                                            step="1" min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Stock Maximo</label>
                                        <small class="text-danger"
                                            v-if="errors.stockMaximo">@{{ errors.stockMaximo }}</small>
                                    </div>
                                </div>
                                <!-- Categoria -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="categoria" name="categoria"
                                            v-model="item.categoria" @blur="validateForm" @change="validateForm">
                                            <option v-for="categoria in categorias" :key="categoria.id"
                                                :value="categoria.id">
                                                @{{ categoria.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Categoria*</label>
                                        <small class="text-danger" v-if="errors.categoria">@{{ errors.categoria }}</small>
                                    </div>
                                </div>
                                <!-- Tipo Venta -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="tipoVenta" name="tipoVenta"
                                            v-model="item.tipoVenta" @blur="validateForm" @change="validateForm">
                                            <option v-for="tipoVenta in tipoVentas" :key="tipoVenta.id"
                                                :value="tipoVenta.id">
                                                @{{ tipoVenta.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Tipo Venta*</label>
                                        <small class="text-danger" v-if="errors.tipoVenta">@{{ errors.tipoVenta }}</small>
                                    </div>
                                </div>
                                <!-- Proveedor -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="proveedor" name="proveedor"
                                            v-model="item.proveedor" @blur="validateForm" @change="validateForm">
                                            <option v-for="proveedor in proveedores" :key="proveedor.id"
                                                :value="proveedor.id">
                                                @{{ proveedor.nombre }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Proveedor*</label>
                                        <small class="text-danger" v-if="errors.proveedor">@{{ errors.proveedor }}</small>
                                    </div>
                                </div>
                                <!-- Unidad searchable select -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="unidad" name="unidad" v-model="item.unidad"
                                            @blur="validateForm" @change="validateForm">
                                            <option v-for="unidad in unidades" :key="unidad.id"
                                                :value="unidad.id">
                                                @{{ unidad.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Unidad*</label>
                                        <small class="text-danger" v-if="errors.unidad">@{{ errors.unidad }}</small>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="SubmitForm"
                            @click="sendForm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Edit modal-->
        <div class="modal fade" id="editProductoModal" tabindex="-1" aria-labelledby="editProductoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editProductoModalLabel">Editar producto</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <!-- Codigo -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <input type="text" class="form-control" id="codigoEdit" name="codigo"
                                            placeholder="Codigo" @blur="validateEditForm" v-model="editItem.codigo">
                                        <label for="floatingInput">Codigo*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.codigo">@{{ editErrors.codigo }}</small>
                                    </div>
                                </div>
                                <!-- Nombre -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.nombre">@{{ editErrors.nombre }}</small>
                                    </div>
                                </div>

                                <!-- Descripcion -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text-area" class="form-control" id="descripcionEdit"
                                            name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                            v-model="editItem.descripcion">
                                        <label for="floatingInput">Descripcion</label>
                                        <small class="text-danger"
                                            v-if="editErrors.descripcion">@{{ editErrors.descripcion }}</small>
                                    </div>
                                </div>
                                <!-- Precio Compra -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioCompraEdit"
                                            name="precioCompra" placeholder="Precio Compra" @blur="validateEditForm"
                                            v-model="editItem.precioCompra" step="0.01" min="0" max="999999"
                                            maxlength="6">
                                        <label for="floatingInput">Precio Compra*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.precioCompra">@{{ editErrors.precioCompra }}</small>
                                    </div>
                                </div>
                                <!-- Precio Venta -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioVentaEdit"
                                            name="precioVenta" placeholder="Precio Venta" @blur="validateEditForm"
                                            v-model="editItem.precioVenta" step="0.01" min="0" max="999999"
                                            maxlength="6">
                                        <label for="floatingInput">Precio Venta*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.precioVenta">@{{ editErrors.precioVenta }}</small>
                                    </div>
                                </div>
                                <!-- Precio Descuento -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioDescuentoEdit"
                                            name="precioDescuento" placeholder="Precio Descuento"
                                            @blur="validateEditForm" v-model="editItem.precioDescuento" step="0.01"
                                            min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Precio Descuento</label>
                                        <small class="text-danger"
                                            v-if="editErrors.precioDescuento">@{{ editErrors.precioDescuento }}</small>
                                    </div>
                                </div>
                                <!-- Precio Especial -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="precioEspecialEdit"
                                            name="precioEspecial" placeholder="Precio Especial" @blur="validateEditForm"
                                            v-model="editItem.precioEspecial" step="0.01" min="0"
                                            max="999999" maxlength="6">
                                        <label for="floatingInput">Precio Especial</label>
                                        <small class="text-danger"
                                            v-if="editErrors.precioEspecial">@{{ editErrors.precioEspecial }}</small>
                                    </div>
                                </div>
                                <!-- Fecha Vencimiento -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="fechaVencimientoEdit"
                                            name="fechaVencimiento" placeholder="Fecha Vencimiento"
                                            @blur="validateEditForm" v-model="editItem.fechaVencimiento">
                                        <label for="floatingInput">Fecha Vencimiento</label>
                                        <small class="text-danger"
                                            v-if="editErrors.fechaVencimiento">@{{ editErrors.fechaVencimiento }}</small>
                                    </div>
                                </div>
                                <!-- Stock -->
                                <div class="form-floating col-md-2" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockEdit" name="stock"
                                            placeholder="Stock" @blur="validateEditForm" v-model="editItem.stock"
                                            step="1" min="0" max="999999" maxlength="6">
                                        <label for="floatingInput">Stock*</label>
                                        <small class="text-danger" v-if="editErrors.stock">@{{ editErrors.stock }}</small>
                                    </div>
                                </div>
                                <!-- Stock Inicial -->
                                <div class="form-floating col-md-2" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockInicialEdit"
                                            name="stockInicial" placeholder="Stock Inicial" @blur="validateEditForm"
                                            v-model="editItem.stockInicial" step="1" min="0" max="999999"
                                            maxlength="6" disabled>
                                        <label for="floatingInput">Stock Inicial*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.stockInicial">@{{ editErrors.stockInicial }}</small>
                                    </div>
                                </div>
                                <!-- Stock Minimo -->
                                <div class="form-floating col-md-2" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockMinimoEdit"
                                            name="stockMinimo" placeholder="Stock Minimo" @blur="validateEditForm"
                                            v-model="editItem.stockMinimo" step="1" min="0" max="999999"
                                            maxlength="6">
                                        <label for="floatingInput">Stock Minimo</label>
                                        <small class="text-danger"
                                            v-if="editErrors.stockMinimo">@{{ editErrors.stockMinimo }}</small>
                                    </div>
                                </div>
                                <!-- Stock Maximo -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stockMaximoEdit"
                                            name="stockMaximo" placeholder="Stock Maximo" @blur="validateEditForm"
                                            v-model="editItem.stockMaximo" step="1" min="0" max="999999"
                                            maxlength="6">
                                        <label for="floatingInput">Stock Maximo</label>
                                        <small class="text-danger"
                                            v-if="editErrors.stockMaximo">@{{ editErrors.stockMaximo }}</small>
                                    </div>
                                </div>
                                <!-- Categoria -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="categoriaEdit" name="categoria"
                                            v-model="editItem.categoria" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="categoria in categorias" :key="categoria.id"
                                                :value="categoria.id">
                                                @{{ categoria.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Categoria*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.categoria">@{{ editErrors.categoria }}</small>
                                    </div>
                                </div>
                                <!-- Tipo Venta -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="tipoVentaEdit" name="tipoVenta"
                                            v-model="editItem.tipoVenta" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="tipoVenta in tipoVentas" :key="tipoVenta.id"
                                                :value="tipoVenta.id">
                                                @{{ tipoVenta.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Tipo Venta*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.tipoVenta">@{{ editErrors.tipoVenta }}</small>
                                    </div>
                                </div>
                                <!-- Proveedor -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="proveedorEdit" name="proveedor"
                                            v-model="editItem.proveedor" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="proveedor in proveedores" :key="proveedor.id"
                                                :value="proveedor.id">
                                                @{{ proveedor.nombre }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Proveedor*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.proveedor">@{{ editErrors.proveedor }}</small>
                                    </div>
                                </div>
                                <!-- Unidad -->
                                <div class="form-floating col-md-3" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="unidadEdit" name="unidad"
                                            v-model="editItem.unidad" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="unidad in unidades" :key="unidad.id"
                                                :value="unidad.id">
                                                @{{ unidad.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Unidad*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.unidad">@{{ editErrors.unidad }}</small>
                                    </div>
                                </div>
                                <!-- Estado -->
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="estadoEdit" name="estado"
                                            v-model="editItem.estado" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="estado in estados" :key="estado.id"
                                                :value="estado.id">
                                                @{{ estado.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Estado*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.estado">@{{ editErrors.estado }}</small>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButtonEdit"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="SubmitFormEdit"
                            @click="sendFormEdit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete modal-->
        <div class="modal fade" id="deleteProductoModal" tabindex="-1" aria-labelledby="deleteProductoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteProductoModalLabel">Eliminar producto</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este producto?</small>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h3>Descripcion: @{{ deleteItem.descripcion }}</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="canceldeleteButton"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="deleteButton"
                            @click="sendDeleteForm">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Show modal-->
        <div class="modal fade" id="showProductoModal" tabindex="-1" aria-labelledby="showProductoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="showProductoModalLabel">Producto</h1>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelShowButton"
                            @click="cleanForm">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                item: {
                    codigo: null,
                    nombre: null,
                    descripcion: null,
                    precioCompra: null,
                    precioVenta: null,
                    precioDescuento: null,
                    precioEspecial: null,
                    fechaVencimiento: null,
                    stock: null,
                    stockInicial: null,
                    stockMinimo: null,
                    stockMaximo: null,
                    categoria: null,
                    tipoVenta: null,
                    proveedor: null,
                    unidad: null,
                },
                editItem: {
                    codigo: null,
                    nombre: null,
                    descripcion: null,
                    precioCompra: null,
                    precioVenta: null,
                    precioDescuento: null,
                    precioEspecial: null,
                    fechaVencimiento: null,
                    stock: null,
                    stockInicial: null,
                    stockMinimo: null,
                    stockMaximo: null,
                    categoria: null,
                    tipoVenta: null,
                    proveedor: null,
                    unidad: null,
                    estado: null,
                    id: null,
                },
                deleteItem: {
                    codigo: null,
                    nombre: null,
                    descripcion: null,
                    precioCompra: null,
                    precioVenta: null,
                    precioDescuento: null,
                    precioEspecial: null,
                    fechaVencimiento: null,
                    stock: null,
                    stockInicial: null,
                    stockMinimo: null,
                    stockMaximo: null,
                    categoria: null,
                    tipoVenta: null,
                    proveedor: null,
                    unidad: null,
                    estado: null,
                    id: null,
                },
                showItem: {
                    codigo: null,
                    nombre: null,
                    descripcion: null,
                    precioCompra: null,
                    precioVenta: null,
                    precioDescuento: null,
                    precioEspecial: null,
                    fechaVencimiento: null,
                    stock: null,
                    stockInicial: null,
                    stockMinimo: null,
                    stockMaximo: null,
                    categoria: null,
                    tipoVenta: null,
                    proveedor: null,
                    unidad: null,
                    estado: null,
                    id: null,
                },
                search: null,
                errors: {},
                editErrors: {},
                productos: [],
                searchProductos: [],
                filtered: [],
                estados: [],
                categorias: [],
                tipoVentas: [],
                proveedores: [],
                unidades: [],
            },
            methods: {
                //Crear
                sendForm() {
                    this.validateForm();

                    if (Object.keys(this.errors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitForm').innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButton').disabled = true;

                        axios({
                            method: 'post',
                            url: '/productos/store',
                            data: this.item
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                '<i class="fas fa-save"></i>';

                            //Cerrar modal
                            document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Producto creado',
                                    text: response.data.success,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.data.error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }

                        }).catch(error => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                'Guardar';

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al crear el producto',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar productos
                            this.getAllProductos();
                        })

                    }
                },
                //Editar
                sendFormEdit() {
                    this.validateEditForm();

                    if (Object.keys(this.editErrors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitFormEdit').innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitFormEdit').disabled = true;
                        document.getElementById('cancelButtonEdit').disabled = true;

                        axios({
                            method: 'post',
                            url: '/productos/edit/' + this.editItem.id,
                            data: this.editItem
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitFormEdit').innerHTML = 'Guardar';

                            //Cerrar modal
                            document.getElementById('cancelButtonEdit').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Producto editada',
                                    text: response.data.success,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.data.error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }


                        }).catch(error => {

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitFormEdit').innerHTML = 'Guardar';

                            //Cerrar modal
                            document.getElementById('cancelButtonEdit').click();

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al editar el producto',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar productos
                            this.getAllProductos();

                        })

                    }
                },
                editProducto(producto) {

                    this.editItem.codigo = producto.codigo;
                    this.editItem.nombre = producto.nombre;
                    this.editItem.descripcion = producto.descripcion;
                    this.editItem.precioCompra = producto.precioCompra;
                    this.editItem.precioVenta = producto.precioVenta;
                    this.editItem.precioDescuento = producto.precioDescuento;
                    this.editItem.precioEspecial = producto.precioEspecial;
                    this.editItem.fechaVencimiento = producto.fechaVencimiento;
                    this.editItem.stock = producto.stock;
                    this.editItem.stockInicial = producto.stockInicial;
                    this.editItem.stockMinimo = producto.stockMinimo;
                    this.editItem.stockMaximo = producto.stockMaximo;
                    this.editItem.categoria = producto.categoria;
                    this.editItem.tipoVenta = producto.tipoVenta;
                    this.editItem.proveedor = producto.proveedor;
                    this.editItem.unidad = producto.unidad;
                    this.editItem.estado = producto.estado;
                    this.editItem.id = producto.id;

                    //dar click al boton de modal
                    document.getElementById('editProductoModalBtn').click();

                },
                //Eliminar
                sendDeleteForm() {
                    //Inhabilitar botones
                    document.getElementById('deleteButton').disabled = true;
                    document.getElementById('canceldeleteButton').disabled = true;

                    //Cambiar icono de boton
                    document.getElementById('deleteButton').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> Eliminando...';

                    axios({
                        method: 'delete',
                        url: '/productos/delete/' + this.deleteItem.id,
                    }).then(response => {

                        if (response.data.error) {
                            //Habilitar boton
                            document.getElementById('deleteButton').disabled = false;
                            document.getElementById('canceldeleteButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('deleteButton').innerHTML = 'Eliminar';

                            //Cerrar modal
                            document.getElementById('canceldeleteButton').click();

                            swal.fire({
                                title: 'Error',
                                text: response.data.error,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {


                            //Habilitar boton
                            document.getElementById('deleteButton').disabled = false;
                            document.getElementById('canceldeleteButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('deleteButton').innerHTML =
                                '<i class="fas fa-trash"></i>';

                            //Cerrar modal
                            document.getElementById('canceldeleteButton').click();

                            swal.fire({
                                title: 'Producto eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        }

                    }).catch(error => {

                        //Habilitar boton
                        document.getElementById('deleteButton').disabled = false;
                        document.getElementById('canceldeleteButton').disabled = false;

                        //Quitar icono de boton
                        document.getElementById('deleteButton').innerHTML = 'Eliminar';

                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al eliminar el producto',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar productos
                        this.getAllProductos();
                    })


                },
                DeleteProducto(producto) {

                    this.deleteItem.codigo = producto.codigo;
                    this.deleteItem.nombre = producto.nombre;
                    this.deleteItem.descripcion = producto.descripcion;
                    this.deleteItem.precioCompra = producto.precioCompra;
                    this.deleteItem.precioVenta = producto.precioVenta;
                    this.deleteItem.precioDescuento = producto.precioDescuento;
                    this.deleteItem.precioEspecial = producto.precioEspecial;
                    this.deleteItem.fechaVencimiento = producto.fechaVencimiento;
                    this.deleteItem.stock = producto.stock;
                    this.deleteItem.stockInicial = producto.stockInicial;
                    this.deleteItem.stockMinimo = producto.stockMinimo;
                    this.deleteItem.stockMaximo = producto.stockMaximo;
                    this.deleteItem.categoria = producto.categoria;
                    this.deleteItem.tipoVenta = producto.tipoVenta;
                    this.deleteItem.proveedor = producto.proveedor;
                    this.deleteItem.unidad = producto.unidad;
                    this.deleteItem.estado = producto.estado;
                    this.deleteItem.id = producto.id;

                    //dar click al boton de modal
                    document.getElementById('deleteProductoModalBtn').click();
                },
                //Mostrar
                viewProducto(producto) {

                    this.showItem.codigo = producto.codigo;
                    this.showItem.nombre = producto.nombre;
                    this.showItem.descripcion = producto.descripcion;
                    this.showItem.precioCompra = producto.precioCompra;
                    this.showItem.precioVenta = producto.precioVenta;
                    this.showItem.precioDescuento = producto.precioDescuento;
                    this.showItem.precioEspecial = producto.precioEspecial;
                    this.showItem.fechaVencimiento = producto.fechaVencimiento;
                    this.showItem.stock = producto.stock;
                    this.showItem.stockInicial = producto.stockInicial;
                    this.showItem.stockMinimo = producto.stockMinimo;
                    this.showItem.stockMaximo = producto.stockMaximo;
                    this.showItem.categoria = producto.categoria;
                    this.showItem.tipoVenta = producto.tipoVenta;
                    this.showItem.proveedor = producto.proveedor;
                    this.showItem.unidad = producto.unidad;
                    this.showItem.estado = producto.estado;
                    this.showItem.id = producto.id;

                    //dar click al boton de modal
                    document.getElementById('showProductoModalBtn').click();

                },
                //Validaciones
                validateForm() {
                    this.errors = {};

                    if (!this.item.nombre) {
                        this.errors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombre').style.border = '1px solid red';
                    } else {
                        document.getElementById('nombre').style.border = '1px solid green';
                    }

                    if (!this.item.codigo) {
                        this.errors.codigo = 'Este campo es obligatorio';
                        document.getElementById('codigo').style.border = '1px solid red';
                    } else {
                        document.getElementById('codigo').style.border = '1px solid green';
                    }

                    if (!this.item.descripcion) {
                        this.errors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcion').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcion').style.border = '1px solid green';
                    }

                    if (!this.item.precioCompra) {
                        this.errors.precioCompra = 'Este campo es obligatorio';
                        document.getElementById('precioCompra').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioCompra').style.border = '1px solid green';
                    }

                    if (!this.item.precioVenta) {
                        this.errors.precioVenta = 'Este campo es obligatorio';
                        document.getElementById('precioVenta').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioVenta').style.border = '1px solid green';
                    }

                    if (!this.item.precioDescuento) {
                        this.errors.precioDescuento = 'Este campo es obligatorio';
                        document.getElementById('precioDescuento').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioDescuento').style.border = '1px solid green';
                    }

                    if (!this.item.precioEspecial) {
                        this.errors.precioEspecial = 'Este campo es obligatorio';
                        document.getElementById('precioEspecial').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioEspecial').style.border = '1px solid green';
                    }

                    if (!this.item.fechaVencimiento) {
                        this.errors.fechaVencimiento = 'Este campo es obligatorio';
                        document.getElementById('fechaVencimiento').style.border = '1px solid red';
                    } else {
                        document.getElementById('fechaVencimiento').style.border = '1px solid green';
                    }

                    if (!this.item.stock) {
                        this.errors.stock = 'Este campo es obligatorio';
                        document.getElementById('stock').style.border = '1px solid red';
                    } else {
                        document.getElementById('stock').style.border = '1px solid green';
                    }

                    if (!this.item.stockInicial) {
                        this.errors.stockInicial = 'Este campo es obligatorio';
                        document.getElementById('stockInicial').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockInicial').style.border = '1px solid green';
                    }

                    if (!this.item.stockMinimo) {
                        this.errors.stockMinimo = 'Este campo es obligatorio';
                        document.getElementById('stockMinimo').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockMinimo').style.border = '1px solid green';
                    }

                    if (!this.item.stockMaximo) {
                        this.errors.stockMaximo = 'Este campo es obligatorio';
                        document.getElementById('stockMaximo').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockMaximo').style.border = '1px solid green';
                    }
                    if (!this.item.categoria) {
                        this.errors.stockMaximo = 'Este campo es obligatorio';
                        document.getElementById('categoria').style.border = '1px solid red';
                    } else {
                        document.getElementById('categoria').style.border = '1px solid green';
                    }

                    if (!this.item.tipoVenta) {
                        this.errors.stockMaximo = 'Este campo es obligatorio';
                        document.getElementById('tipoVenta').style.border = '1px solid red';
                    } else {
                        document.getElementById('tipoVenta').style.border = '1px solid green';
                    }

                    if (!this.item.proveedor) {
                        this.errors.stockMaximo = 'Este campo es obligatorio';
                        document.getElementById('proveedor').style.border = '1px solid red';
                    } else {
                        document.getElementById('proveedor').style.border = '1px solid green';
                    }

                    if (!this.item.unidad) {
                        this.errors.stockMaximo = 'Este campo es obligatorio';
                        document.getElementById('unidad').style.border = '1px solid red';
                    } else {
                        document.getElementById('unidad').style.border = '1px solid green';
                    }

                    this.validateProductoname();
                },
                validateEditForm() {

                    editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('nombreEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.codigo) {
                        this.editErrors.codigo = 'Este campo es obligatorio';
                        document.getElementById('codigoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('codigoEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcionEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.precioCompra) {
                        this.editErrors.precioCompra = 'Este campo es obligatorio';
                        document.getElementById('precioCompraEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioCompraEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.precioVenta) {
                        this.editErrors.precioVenta = 'Este campo es obligatorio';
                        document.getElementById('precioVentaEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioVentaEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.precioDescuento) {
                        this.editErrors.precioDescuento = 'Este campo es obligatorio';
                        document.getElementById('precioDescuentoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioDescuentoEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.precioEspecial) {
                        this.editErrors.precioEspecial = 'Este campo es obligatorio';
                        document.getElementById('precioEspecialEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('precioEspecialEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.fechaVencimiento) {
                        this.editErrors.fechaVencimiento = 'Este campo es obligatorio';
                        document.getElementById('fechaVencimientoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('fechaVencimientoEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.stock) {
                        this.editErrors.stock = 'Este campo es obligatorio';
                        document.getElementById('stockEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.stockInicial) {
                        this.editErrors.stockInicial = 'Este campo es obligatorio';
                        document.getElementById('stockInicialEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockInicialEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.stockMinimo) {
                        this.editErrors.stockMinimo = 'Este campo es obligatorio';
                        document.getElementById('stockMinimoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockMinimoEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.stockMaximo) {
                        this.editErrors.stockMaximo = 'Este campo es obligatorio';
                        document.getElementById('stockMaximoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('stockMaximoEdit').style.border = '1px solid green';
                    }

                    document.getElementById('categoriaEdit').style.border = '1px solid green';
                    document.getElementById('tipoVentaEdit').style.border = '1px solid green';
                    document.getElementById('proveedorEdit').style.border = '1px solid green';
                    document.getElementById('unidadEdit').style.border = '1px solid green';
                    document.getElementById('estadoEdit').style.border = '1px solid green';

                    this.validateEditProductoname();
                },
                validateProductoname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9.]{3,}$/;

                    if (!regex.test(this.item.nombre)) {
                        this.errors.nombre =
                            'El producto debe tener al menos 3 caracteres y no contener caracteres especiales';
                    }

                    for (let i = 0; i < this.productos.length; i++) {
                        if (this.productos[i].nombre == this.item.nombre) {
                            this.errors.nombre = 'El producto ya existe';
                        }
                    }
                },
                validateEditProductoname() {

                    this.editErrors = {};

                    let regex = /^[ a-zA-Z0-9.]{5,}$/;

                    if (!regex.test(this.editItem.nombre)) {
                        this.editErrors.nombre =
                            'El producto debe tener al menos 3 caracteres y no contener espacios especiales';
                    }

                    //Eliminar del array el producto que se esta editando
                    this.productos = this.productos.filter(producto => producto.id != this.editItem.id);

                    //recorrer this.productos
                    for (let i = 0; i < this.productos.length; i++) {
                        if (this.productos[i].nombre == this.editItem.nombre) {
                            this.editErrors.nombre = 'El producto ya existe';
                        }
                    }

                },
                formatDate(date) {
                    let fecha = new Date(date);
                    let options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return fecha.toLocaleDateString('es-ES', options);

                },
                //Limpiar formulario y busqueda
                searchFn() {
                    let search = this.search.toLowerCase();
                    let productos = this.searchProductos;

                    try {
                        this.filtered = productos.filter(producto => {
                            return producto.nombre.toLowerCase().includes(search) ||
                                producto.descripcion.toLowerCase().includes(search) ||
                                producto.codigo.toLowerCase().includes(search);
                        });
                    } catch (error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar el producto',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }

                    this.productos = this.filtered;
                },
                cleanForm() {

                    this.item = {
                        codigo: null,
                        nombre: null,
                        descripcion: null,
                        precioCompra: null,
                        precioVenta: null,
                        precioDescuento: null,
                        precioEspecial: null,
                        fechaVencimiento: null,
                        stock: null,
                        stockInicial: null,
                        stockMinimo: null,
                        stockMaximo: null,
                        categoria: null,
                        tipoVenta: null,
                        proveedor: null,
                        unidad: null,
                    };
                    this.errors = {};
                    this.editErrors = {};
                    this.search = null;
                    //this.productos = [];
                    this.editItem = {
                        codigo: null,
                        nombre: null,
                        descripcion: null,
                        precioCompra: null,
                        precioVenta: null,
                        precioDescuento: null,
                        precioEspecial: null,
                        fechaVencimiento: null,
                        stock: null,
                        stockInicial: null,
                        stockMinimo: null,
                        stockMaximo: null,
                        categoria: null,
                        tipoVenta: null,
                        proveedor: null,
                        unidad: null,
                        estado: null,
                        id: null,
                    };
                    this.deleteItem = {
                        codigo: null,
                        nombre: null,
                        descripcion: null,
                        precioCompra: null,
                        precioVenta: null,
                        precioDescuento: null,
                        precioEspecial: null,
                        fechaVencimiento: null,
                        stock: null,
                        stockInicial: null,
                        stockMinimo: null,
                        stockMaximo: null,
                        categoria: null,
                        tipoVenta: null,
                        proveedor: null,
                        unidad: null,
                        estado: null,
                        id: null,
                    };
                    this.showItem = {
                        codigo: null,
                        nombre: null,
                        descripcion: null,
                        precioCompra: null,
                        precioVenta: null,
                        precioDescuento: null,
                        precioEspecial: null,
                        fechaVencimiento: null,
                        stock: null,
                        stockInicial: null,
                        stockMinimo: null,
                        stockMaximo: null,
                        categoria: null,
                        tipoVenta: null,
                        proveedor: null,
                        unidad: null,
                        estado: null,
                        id: null,
                    };

                    document.getElementById('nombre').style.border = '1px solid #ced4da';
                    document.getElementById('codigo').style.border = '1px solid #ced4da';
                    document.getElementById('descripcion').style.border = '1px solid #ced4da';
                    document.getElementById('precioCompra').style.border = '1px solid #ced4da';
                    document.getElementById('precioVenta').style.border = '1px solid #ced4da';
                    document.getElementById('precioDescuento').style.border = '1px solid #ced4da';
                    document.getElementById('precioEspecial').style.border = '1px solid #ced4da';
                    document.getElementById('fechaVencimiento').style.border = '1px solid #ced4da';
                    document.getElementById('stock').style.border = '1px solid #ced4da';
                    document.getElementById('stockInicial').style.border = '1px solid #ced4da';
                    document.getElementById('stockMinimo').style.border = '1px solid #ced4da';
                    document.getElementById('stockMaximo').style.border = '1px solid #ced4da';
                    document.getElementById('categoria').style.border = '1px solid #ced4da';
                    document.getElementById('tipoVenta').style.border = '1px solid #ced4da';
                    document.getElementById('proveedor').style.border = '1px solid #ced4da';
                    document.getElementById('unidad').style.border = '1px solid #ced4da';

                    document.getElementById('nombreEdit').style.border = '1px solid #ced4da';
                    document.getElementById('codigoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('descripcionEdit').style.border = '1px solid #ced4da';
                    document.getElementById('precioCompraEdit').style.border = '1px solid #ced4da';
                    document.getElementById('precioVentaEdit').style.border = '1px solid #ced4da';
                    document.getElementById('precioDescuentoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('precioEspecialEdit').style.border = '1px solid #ced4da';
                    document.getElementById('fechaVencimientoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('stockEdit').style.border = '1px solid #ced4da';
                    document.getElementById('stockInicialEdit').style.border = '1px solid #ced4da';
                    document.getElementById('stockMinimoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('stockMaximoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('categoriaEdit').style.border = '1px solid #ced4da';
                    document.getElementById('tipoVentaEdit').style.border = '1px solid #ced4da';
                    document.getElementById('proveedorEdit').style.border = '1px solid #ced4da';
                    document.getElementById('unidadEdit').style.border = '1px solid #ced4da';
                    document.getElementById('estadoEdit').style.border = '1px solid #ced4da';

                    this.productos = this.searchProductos;
                },
                cleanSearch() {
                    this.search = null;
                    this.productos = this.searchProductos;
                },
                //Obtener recursos
                async getAllProductos() {
                    let response = await fetch('/allProductos');
                    let data = await response.json();
                    this.productos = data;
                    this.searchProductos = data;

                    //doubleParse
                    for (let i = 0; i < this.productos.length; i++) {
                        this.productos[i].precioCompra = parseFloat(this.productos[i].precioCompra).toFixed(2);
                        this.productos[i].precioVenta = parseFloat(this.productos[i].precioVenta).toFixed(2);
                        this.productos[i].precioDescuento = parseFloat(this.productos[i].precioDescuento)
                            .toFixed(2);
                        this.productos[i].precioEspecial = parseFloat(this.productos[i].precioEspecial).toFixed(
                            2);
                    }

                },
                async getAllEstados() {
                    let response = await fetch('/allEstados');
                    let data = await response.json();
                    this.estados = data;
                },
                async getAllCategorias() {
                    let response = await fetch('/allCategorias');
                    let data = await response.json();
                    this.categorias = data;
                },
                async getAllTipoVentas() {
                    let response = await fetch('/allTipoVenta');
                    let data = await response.json();
                    this.tipoVentas = data;
                },
                async getAllProveedores() {
                    let response = await fetch('/allProveedores');
                    let data = await response.json();
                    this.proveedores = data;
                },
                async getAllUnidades() {
                    let response = await fetch('/allUnidades');
                    let data = await response.json();
                    this.unidades = data;
                }

            },
            mounted() {
                this.getAllCategorias();
                this.getAllTipoVentas();
                this.getAllProveedores();
                this.getAllUnidades();
                this.getAllEstados();
                this.getAllProductos();
            }
        });
    </script>
@endsection
