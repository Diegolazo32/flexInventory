@extends('Layouts.Navigation')

@section('title', 'Productos')

@section('content')
<div id="app">
    <div class="card hoverCard">
        <div class="card-header">
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-lg-10">
                    <h1>Productos</h1>
                    <small class="text-muted"></small>
                </div>
                <!-- Botones de accion -->
                <div class="col-lg-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#crearProductoModal" style="height: 40px;">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editProductoModalBtn"
                        data-bs-target="#editProductoModal" style="height: 40px;" hidden>
                        Editar producto
                    </button>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="getAllLotesModalBtn"
                        data-bs-target="#getAllLotesModal" style="height: 40px;" hidden>
                        Ver lotes
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
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" name="search"
                                placeholder="Buscar por codigo o nombre" v-model="search">
                            <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                        </div>
                        <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                            <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                @click="getAllProductos"><i class="fa-solid fa-magnifying-glass"></i></button>
                            <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla de productos -->
        <div class="row">
            <div class="card-body">

        <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
            <i class="fas fa-spinner fa-spin"></i> Cargando...
        </div>

                <div v-if="productoError" class="alert alert-danger" role="alert">
                    <h3>@{{ productoError }}</h3>
                </div>

                <div v-if="productos.length == 0 && !loading && !productoError" class="alert alert-warning"
                    role="alert">
                    <h3>No hay productos registrados</h3>
                </div>

                <div v-if="productos.length > 0" class="table-responsive">

                    <table ref="table" class="table table-striped table-hover" style="text-align: center;">
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
                                <th scope="col">Stock Minimo</th>
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
                                <td>$@{{ parseDouble(producto.precioCompra) ?? '-' }}</td>
                                <td>$@{{ parseDouble(producto.precioVenta) ?? '-' }}</td>
                                <td>$@{{ parseDouble(producto.precioDescuento) ?? '-' }}</td>
                                <td>$@{{ parseDouble(producto.precioEspecial) ?? '-' }}</td>
                                <td v-if="producto.fechaVencimiento < today">
                                    <span class="badge bg-danger">Expirado</span>
                                </td>
                                <td v-else>@{{ formatDate(producto.fechaVencimiento) ?? '-' }}</td>
                                <td>@{{ producto.stock ?? '-' }}</td>
                                <td>@{{ producto.stockMinimo ?? '-' }}</td>
                                <td>@{{ categorias.find(categoria => categoria.id == producto.categoria).descripcion }} </td>
                                <td>@{{ tipoVentas.find(tipoVenta => tipoVenta.id == producto.tipoVenta).descripcion }}</td>
                                <td>@{{ proveedores.find(proveedor => proveedor.id == producto.proveedor).nombre }}</td>
                                <td>@{{ unidades.find(unidad => unidad.id == producto.unidad).descripcion }}</td>
                                <td v-if="producto.estado == 1">
                                    <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                    <span v-else class="badge bg-success">@{{ estados.find(estado => estado.id == producto.estado).descripcion }}</span>
                                </td>
                                <td v-else>
                                    <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                    <span v-else class="badge bg-danger">@{{ estados.find(estado => estado.id == producto.estado).descripcion }}</span>
                                </td>
                                <td>
                                    <button id="editBTN" class="btn btn-primary" @click="editProducto(producto)">
                                        <i class="fas fa-pencil"></i>
                                    </button>

                                    <button id="showBTN" class="btn btn-warning" @click="viewProducto(producto)">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button id="lotesBte" class="btn btn-success" @click="getAllLotes(producto)">
                                        <i class="fa-solid fa-table-cells"></i>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center" style="gap: 10px;">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :disabled="page === 1">
                        <a class="page-link" href="#" aria-label="Previous" @click="pageMinus">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item" v-for="pageNumber in totalPages" :key="pageNumber"
                        :class="{ active: pageNumber === page }">
                        <a class="page-link" href="#" @click="specificPage(pageNumber)">
                            @{{ pageNumber }}
                        </a>
                    </li>
                    <li class="page-item" :disabled="page === totalPages">
                        <a class="page-link" href="#" aria-label="Next" @click="pagePlus">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
                <ul class="pagination justify-content-center">

                    <li class="page-item">
                        <select class="form-select" v-model="per_page" @change="changePerPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="crearProductoModal" tabindex="-1" aria-labelledby="crearProductoModalLabel"
        aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                    <h1 class="modal-title fs-5" id="crearProductoModalLabel">Crear producto </h1>
                    <small class="text-muted"> Los campos marcados con * son obligatorios</small><br>
                    <small class="text-muted"> Al crear un producto, se creara un lote con la cantidad asignada en
                        'stock' y con fecha de vencimiento
                        asignada en 'fecha vencimiento'
                    </small>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <form ref="form" action="{{ route('productos.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Codigo -->
                            <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="codigo" name="codigo"
                                        placeholder="Codigo" @blur="validateForm" v-model="item.codigo">
                                    <label for="floatingInput">Codigo*</label>
                                    <div class="invalid-tooltip" v-if="errors.codigo">@{{ errors.codigo }}</div>
                                </div>
                            </div>
                            <!-- Nombre -->
                            <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Nombre" @blur="validateForm" v-model="item.nombre">
                                    <label for="floatingInput">Nombre*</label>
                                    <div class="invalid-tooltip" v-if="errors.nombre">@{{ errors.nombre }}</div>
                                </div>
                            </div>
                            <!-- Descripcion -->
                            <div class="form-floating col-lg-12" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <textarea type="text-area" class="form-control" id="descripcion" name="descripcion" style="height: 100px;"
                                        placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion"></textarea>
                                    <label for="floatingInput">Descripcion</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.descripcion">@{{ errors.descripcion }}</div>
                                </div>
                            </div>
                            <!-- Precio Compra -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioCompra" name="precioCompra"
                                        placeholder="Precio Compra" @blur="validateForm" v-model="item.precioCompra"
                                        step="0.01" min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Compra*</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.precioCompra">@{{ errors.precioCompra }}</div>
                                </div>
                            </div>
                            <!-- Precio Venta -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioVenta" name="precioVenta"
                                        placeholder="Precio Venta" @blur="validateForm" v-model="item.precioVenta"
                                        step="0.01" min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Venta*</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.precioVenta">@{{ errors.precioVenta }}</div>
                                </div>
                            </div>
                            <!-- Precio Descuento -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioDescuento"
                                        name="precioDescuento" placeholder="Precio Descuento" @blur="validateForm"
                                        v-model="item.precioDescuento" step="0.01" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Precio Descuento</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.precioDescuento">@{{ errors.precioDescuento }}</div>
                                </div>
                            </div>
                            <!-- Precio Especial -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioEspecial"
                                        name="precioEspecial" placeholder="Precio Especial" @blur="validateForm"
                                        v-model="item.precioEspecial" step="0.01" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Precio Especial</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.precioEspecial">@{{ errors.precioEspecial }}</div>
                                </div>
                            </div>
                            <!-- Fecha Vencimiento -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="fechaVencimiento"
                                        name="fechaVencimiento" placeholder="Fecha Vencimiento" @blur="validateForm"
                                        v-model="item.fechaVencimiento">
                                    <label for="floatingInput">Fecha Vencimiento</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.fechaVencimiento">@{{ errors.fechaVencimiento }}</div>
                                </div>
                            </div>
                            <!-- Stock -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stock" name="stock"
                                        placeholder="Stock" @blur="validateForm" v-model="item.stock" step="1"
                                        min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Stock*</label>
                                    <div class="invalid-tooltip" v-if="errors.stock">@{{ errors.stock }}</div>
                                </div>
                            </div>
                            <!-- Stock Inicial -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockInicial" name="stockInicial"
                                        placeholder="Stock Inicial" @blur="validateForm" v-model="item.stockInicial"
                                        step="1" min="0" max="999999" maxlength="6" disabled>
                                    <label for="floatingInput">Stock Inicial*</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.stockInicial">@{{ errors.stockInicial }}</div>
                                </div>
                            </div>
                            <!-- Stock Minimo -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockMinimo" name="stockMinimo"
                                        placeholder="Stock Minimo" @blur="validateForm" v-model="item.stockMinimo"
                                        step="1" min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Stock Minimo</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.stockMinimo">@{{ errors.stockMinimo }}</div>
                                </div>
                            </div>
                            <!-- Stock Maximo -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockMaximo" name="stockMaximo"
                                        placeholder="Stock Maximo" @blur="validateForm" v-model="item.stockMaximo"
                                        step="1" min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Stock Maximo</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.stockMaximo">@{{ errors.stockMaximo }}</div>
                                </div>
                            </div>
                            <!-- Categoria -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="categoria" name="categoria"
                                        v-model="item.categoria" @blur="validateForm" @change="validateForm">
                                        <option v-for="categoria in categorias" :key="categoria.id"
                                            :value="categoria.id">
                                            @{{ categoria.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Categoria*</label>
                                    <div class="invalid-tooltip" v-if="errors.categoria">@{{ errors.categoria }}</div>
                                </div>
                            </div>
                            <!-- Tipo Venta -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="tipoVenta" name="tipoVenta"
                                        v-model="item.tipoVenta" @blur="validateForm" @change="validateForm">
                                        <option v-for="tipoVenta in tipoVentas" :key="tipoVenta.id"
                                            :value="tipoVenta.id">
                                            @{{ tipoVenta.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Tipo Venta*</label>
                                    <div class="invalid-tooltip" v-if="errors.tipoVenta">@{{ errors.tipoVenta }}</div>
                                </div>
                            </div>
                            <!-- Proveedor -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="proveedor" name="proveedor"
                                        v-model="item.proveedor" @blur="validateForm" @change="validateForm">
                                        <option v-for="proveedor in proveedores" :key="proveedor.id"
                                            :value="proveedor.id">
                                            @{{ proveedor.nombre }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Proveedor*</label>
                                    <div class="invalid-tooltip" v-if="errors.proveedor">@{{ errors.proveedor }}</div>
                                </div>
                            </div>
                            <!-- Unidad searchable select -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="unidad" name="unidad" v-model="item.unidad"
                                        @blur="validateForm" @change="validateForm">
                                        <option v-for="unidad in unidades" :key="unidad.id"
                                            :value="unidad.id">
                                            @{{ unidad.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Unidad*</label>
                                    <div class="invalid-tooltip" v-if="errors.unidad">@{{ errors.unidad }}</div>
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
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                    <h1 class="modal-title fs-5" id="editProductoModalLabel">Editar producto</h1>
                    <small class="text-muted"> Los campos marcados con * son obligatorios</small><br>
                    <small class="text-muted">Para modificar la fecha de vencimiento de un producto, debe modificarla
                        en la seccion de lotes, y modificar la del lote entero. </small>

                </div>
                <div class="modal-body" style="padding: 25px;">
                    <form ref="formEdit">
                        @csrf
                        <div class="row">
                            <!-- Codigo -->
                            <div class="form-floating col-lg-4" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">

                                    <input type="text" class="form-control" id="codigoEdit" name="codigo"
                                        placeholder="Codigo" @blur="validateEditForm" v-model="editItem.codigo">
                                    <label for="floatingInput">Codigo*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.codigo">@{{ editErrors.codigo }}</div>
                                </div>
                            </div>
                            <!-- Nombre -->
                            <div class="form-floating col-lg-4" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                        placeholder="Nombre" @blur="validateEditForm" v-model="editItem.nombre">
                                    <label for="floatingInput">Nombre*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.nombre">@{{ editErrors.nombre }}</div>
                                </div>
                            </div>

                            <!-- Descripcion -->
                            <div class="form-floating col-lg-4" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="text-area" class="form-control" id="descripcionEdit"
                                        name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                        v-model="editItem.descripcion">
                                    <label for="floatingInput">Descripcion</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.descripcion">@{{ editErrors.descripcion }}</div>
                                </div>
                            </div>
                            <!-- Precio Compra -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioCompraEdit"
                                        name="precioCompra" placeholder="Precio Compra" @blur="validateEditForm"
                                        v-model="editItem.precioCompra" step="0.01" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Precio Compra*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.precioCompra">@{{ editErrors.precioCompra }}</div>
                                </div>
                            </div>
                            <!-- Precio Venta -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioVentaEdit"
                                        name="precioVenta" placeholder="Precio Venta" @blur="validateEditForm"
                                        v-model="editItem.precioVenta" step="0.01" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Precio Venta*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.precioVenta">@{{ editErrors.precioVenta }}</div>
                                </div>
                            </div>
                            <!-- Precio Descuento -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioDescuentoEdit"
                                        name="precioDescuento" placeholder="Precio Descuento"
                                        @blur="validateEditForm" v-model="editItem.precioDescuento" step="0.01"
                                        min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Descuento</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.precioDescuento">@{{ editErrors.precioDescuento }}</div>
                                </div>
                            </div>
                            <!-- Precio Especial -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="precioEspecialEdit"
                                        name="precioEspecial" placeholder="Precio Especial" @blur="validateEditForm"
                                        v-model="editItem.precioEspecial" step="0.01" min="0"
                                        max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Especial</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.precioEspecial">@{{ editErrors.precioEspecial }}</div>
                                </div>
                            </div>
                            <!-- Fecha Vencimiento -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="date" class="form-control" id="fechaVencimientoEdit"
                                        name="fechaVencimiento" placeholder="Fecha Vencimiento"
                                        @blur="validateEditForm" v-model="editItem.fechaVencimiento">
                                    <label for="floatingInput">Fecha Vencimiento</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.fechaVencimiento">@{{ editErrors.fechaVencimiento }}</div>
                                </div>
                            </div>
                            <!-- Stock -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockEdit" name="stock"
                                        placeholder="Stock" @blur="validateEditForm" v-model="editItem.stock"
                                        step="1" min="0" max="999999" maxlength="6" disabled>
                                    <label for="floatingInput">Stock*</label>
                                    <div class="invalid-tooltip" v-if="editErrors.stock">@{{ editErrors.stock }}</div>
                                </div>
                            </div>
                            <!-- Stock Inicial -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockInicialEdit"
                                        name="stockInicial" placeholder="Stock Inicial" @blur="validateEditForm"
                                        v-model="editItem.stockInicial" step="1" min="0" max="999999"
                                        maxlength="6" disabled>
                                    <label for="floatingInput">Stock Inicial*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.stockInicial">@{{ editErrors.stockInicial }}</div>
                                </div>
                            </div>
                            <!-- Stock Minimo -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockMinimoEdit"
                                        name="stockMinimo" placeholder="Stock Minimo" @blur="validateEditForm"
                                        v-model="editItem.stockMinimo" step="1" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Stock Minimo</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.stockMinimo">@{{ editErrors.stockMinimo }}</div>
                                </div>
                            </div>
                            <!-- Stock Maximo -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="stockMaximoEdit"
                                        name="stockMaximo" placeholder="Stock Maximo" @blur="validateEditForm"
                                        v-model="editItem.stockMaximo" step="1" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Stock Maximo</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.stockMaximo">@{{ editErrors.stockMaximo }}</div>
                                </div>
                            </div>
                            <!-- Categoria -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
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
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.categoria">@{{ editErrors.categoria }}</div>
                                </div>
                            </div>
                            <!-- Tipo Venta -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
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
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.tipoVenta">@{{ editErrors.tipoVenta }}</div>
                                </div>
                            </div>
                            <!-- Proveedor -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
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
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.proveedor">@{{ editErrors.proveedor }}</div>
                                </div>
                            </div>
                            <!-- Unidad -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
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
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.unidad">@{{ editErrors.unidad }}</div>
                                </div>
                            </div>
                            <!-- Estado -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="estadoEdit" name="estado"
                                        :disabled="estados.error" v-model="editItem.estado" @blur="validateEditForm"
                                        @change="validateEditForm">
                                        <option v-for="estado in estados" :key="estado.id"
                                            :value="estado.id">
                                            @{{ estado.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Estado*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.estado">@{{ editErrors.estado }}</div>

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

    <!--Show modal-->
    <div class="modal fade" id="showProductoModal" tabindex="-1" aria-labelledby="showProductoModalLabel"
        aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                    <h1 class="modal-title fs-5" id="showProductoModalLabel">Producto</h1>
                </div>
                <div class="modal-body text-center" style="padding: 25px;">
                    <form ref="formEdit">
                        @csrf
                        <div class="row">
                            <!-- Codigo -->
                            <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="text" class="form-control" id="codigoEdit"
                                        name="codigo" placeholder="Codigo" v-model="showItem.codigo">
                                    <label for="floatingInput">Codigo*</label>
                                </div>
                            </div>
                            <!-- Nombre -->
                            <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="text" class="form-control" id="nombreEdit"
                                        name="nombre" placeholder="Nombre" v-model="showItem.nombre">
                                    <label for="floatingInput">Nombre*</label>

                                </div>
                            </div>

                            <!-- Descripcion -->
                            <div class="form-floating col-lg-12" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <textarea disabled type="text-area" class="form-control" id="descripcionEdit" style="height: 100px;"
                                        name="descripcion" placeholder="Descripcion" v-model="showItem.descripcion"></textarea>
                                    <label for="floatingInput">Descripcion</label>
                                </div>
                            </div>
                            <!-- Precio Compra -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="precioCompraEdit"
                                        name="precioCompra" placeholder="Precio Compra"
                                        v-model="showItem.precioCompra" step="0.01" min="0" max="999999"
                                        maxlength="6">
                                    <label for="floatingInput">Precio Compra*</label>
                                </div>
                            </div>
                            <!-- Precio Venta -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="precioVentaEdit"
                                        name="precioVenta" placeholder="Precio Venta" v-model="showItem.precioVenta"
                                        step="0.01" min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Venta*</label>
                                </div>
                            </div>
                            <!-- Precio Descuento -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="precioDescuentoEdit"
                                        name="precioDescuento" placeholder="Precio Descuento"
                                        v-model="showItem.precioDescuento" step="0.01" min="0"
                                        max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Descuento</label>
                                </div>
                            </div>
                            <!-- Precio Especial -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="precioEspecialEdit"
                                        name="precioEspecial" placeholder="Precio Especial"
                                        v-model="showItem.precioEspecial" step="0.01" min="0"
                                        max="999999" maxlength="6">
                                    <label for="floatingInput">Precio Especial</label>
                                </div>
                            </div>
                            <!-- Fecha Vencimiento -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="date" class="form-control" id="fechaVencimientoEdit"
                                        name="fechaVencimiento" placeholder="Fecha Vencimiento"
                                        v-model="showItem.fechaVencimiento">
                                    <label for="floatingInput">Fecha Vencimiento</label>
                                </div>
                            </div>
                            <!-- Stock -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="stockEdit"
                                        name="stock" placeholder="Stock" v-model="showItem.stock" step="1"
                                        min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Stock*</label>
                                </div>
                            </div>
                            <!-- Stock Inicial -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="stockInicialEdit"
                                        name="stockInicial" placeholder="Stock Inicial"
                                        v-model="showItem.stockInicial" step="1" min="0" max="999999"
                                        maxlength="6" disabled>
                                    <label for="floatingInput">Stock Inicial*</label>
                                </div>
                            </div>
                            <!-- Stock Minimo -->
                            <div class="form-floating col-lg-2" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="stockMinimoEdit"
                                        name="stockMinimo" placeholder="Stock Minimo" v-model="showItem.stockMinimo"
                                        step="1" min="0" max="999999" maxlength="6">
                                    <label for="floatingInput">Stock Minimo</label>
                                </div>
                            </div>
                            <!-- Stock Maximo -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input disabled type="number" class="form-control" id="stockMaximoEdit"
                                        name="stockMaximo" placeholder="Stock Maximo" v-model="showItem.stockMaximo"
                                        step="1" min="0" max="999999" maxlength="6" disabled>
                                    <label for="floatingInput">Stock Maximo</label>
                                </div>
                            </div>
                            <!-- Categoria -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select disabled class="form-select" id="categoriaEdit" name="categoria"
                                        v-model="showItem.categoria">
                                        <option v-for="categoria in categorias" :key="categoria.id"
                                            :value="categoria.id">
                                            @{{ categoria.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Categoria*</label>
                                </div>
                            </div>
                            <!-- Tipo Venta -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select disabled class="form-select" id="tipoVentaEdit" name="tipoVenta"
                                        v-model="showItem.tipoVenta">
                                        <option v-for="tipoVenta in tipoVentas" :key="tipoVenta.id"
                                            :value="tipoVenta.id">
                                            @{{ tipoVenta.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Tipo Venta*</label>

                                </div>
                            </div>
                            <!-- Proveedor -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select disabled class="form-select" id="proveedorEdit" name="proveedor"
                                        v-model="showItem.proveedor">
                                        <option v-for="proveedor in proveedores" :key="proveedor.id"
                                            :value="proveedor.id">
                                            @{{ proveedor.nombre }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Proveedor*</label>

                                </div>
                            </div>
                            <!-- Unidad -->
                            <div class="form-floating col-lg-3" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <select disabled class="form-select" id="unidadEdit" name="unidad"
                                        v-model="showItem.unidad">
                                        <option v-for="unidad in unidades" :key="unidad.id"
                                            :value="unidad.id">
                                            @{{ unidad.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Unidad*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.unidad">@{{ editErrors.unidad }}</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelShowButton"
                        @click="cleanForm">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--lotes modal-->
    <div class="modal fade" id="getAllLotesModal" tabindex="-1" aria-labelledby="getAllLotesModalLabel"
        aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header row" style="display: flex; justify-content: space-between;">
                    <div class="col-lg-6">
                        <h1 class="modal-title fs-5" id="getAllLotesModalLabel">Lotes del producto</h1>
                        <small class="text-muted">Aqui puede modificar la fecha de vencimiento de un lote</small><br>
                    </div>

                    <div class="col-lg-6" style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn btn-success" id="editLoteButton" @click="toggleEdit">
                            <i v-if="!editMode" class="fas fa-edit"></i>
                            <i v-else class="fas fa-times"></i>
                        </button>
                    </div>


                </div>
                <div class="modal-body text-center" style="padding: 25px;">


                    <div v-if="loading" role="alert" id="loading">
                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                    </div>


                    <table v-if="!loading" ref="table" class="table table-striped table-hover"
                        style="text-align: center;">
                        <thead>
                            <tr>
                                <th scope="col">Codigo</th>
                                <th scope="col">Lote n°</th>
                                <th scope="col">Fecha Vencimiento</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- vue foreach -->
                            <tr v-for='lote in lotes' :key="lote.id">
                                <td>@{{ lote.codigo }}</td>
                                <td>@{{ lote.numero }}</td>
                                <td><input :disabled="!editMode" type="date" class="form-control"
                                        id="fechaVencimientoLote" @blur="validateLote"
                                        v-model="lote.fechaVencimiento">
                                    <div class="invalid-tooltip"
                                        v-if="lotesError.fechaVencimiento">@{{ lotesError.fechaVencimiento }}</div>
                                </td>
                                <td>@{{ lote.cantidad }}
                                </td>
                                <td v-if="lote.estado == 1">
                                    <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                    <span v-else class="badge bg-success">@{{ estados.find(estado => estado.id == lote.estado).descripcion }}</span>
                                </td>
                                <td v-else>
                                    <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                    <span v-else class="badge bg-danger">@{{ estados.find(estado => estado.id == lote.estado).descripcion }}</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelLotesButton"
                        @click="cleanForm">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="submitLotesButton"
                        @click="sendLotes(lotes[0].producto)">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            editMode: false,
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
            productoError: '',
            searchProductos: [],
            filtered: [],
            estados: [],
            categorias: [],
            tipoVentas: [],
            proveedores: [],
            unidades: [],
            searchError: '',
            loading: true,
            page: 1,
            per_page: 10,
            total: 0,
            totalPages: 0,
            nextPageUrl: '',
            prevPageUrl: '',
            lotes: [],
            lotesError: {},
            today: new Date().toISOString().split('T')[0],

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
                        document.getElementById('SubmitForm').innerHTML = 'Guardar';

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
                                title: 'Producto editado',
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

                this.item.stockInicial = this.item.stock;

                if (!this.item.nombre) {
                    this.errors.nombre = 'Este campo es obligatorio';
                    document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('nombre').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.codigo) {
                    this.errors.codigo = 'Este campo es obligatorio';
                    document.getElementById('codigo').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('codigo').setAttribute('class', 'form-control is-valid');
                }

                document.getElementById('descripcion').setAttribute('class', 'form-control is-valid');

                if (!this.item.precioCompra) {
                    this.errors.precioCompra = 'Este campo es obligatorio';
                    document.getElementById('precioCompra').setAttribute('class', 'form-control is-invalid');
                } else if (this.item.precioCompra < 0) {
                    this.errors.precioCompra = 'El precio no puede ser negativo';
                    document.getElementById('precioCompra').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioCompra').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.precioVenta) {
                    this.errors.precioVenta = 'Este campo es obligatorio';
                    document.getElementById('precioVenta').setAttribute('class', 'form-control is-invalid');
                } else if (this.item.precioVenta < 0) {
                    this.errors.precioVenta = 'El precio no puede ser negativo';
                    document.getElementById('precioVenta').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioVenta').setAttribute('class', 'form-control is-valid');
                }

                if (this.item.precioDescuento < 0) {
                    this.errors.precioDescuento = 'El precio no puede ser negativo';
                    document.getElementById('precioDescuento').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioDescuento').setAttribute('class', 'form-control is-valid');
                }

                if (this.item.precioEspecial < 0) {
                    this.errors.precioEspecial = 'El precio no puede ser negativo';
                    document.getElementById('precioEspecial').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioEspecial').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.stock) {
                    this.errors.stock = 'Este campo es obligatorio';
                    document.getElementById('stock').setAttribute('class', 'form-control is-invalid');
                } else if (this.item.stock < 0) {
                    this.errors.stock = 'El stock no puede ser negativo';
                    document.getElementById('stock').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stock').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.stockInicial) {
                    this.errors.stockInicial = 'Este campo es obligatorio';
                    document.getElementById('stockInicial').setAttribute('class', 'form-control is-invalid');
                } else if (this.item.stockInicial < 0) {
                    this.errors.stockInicial = 'El stock no puede ser negativo';
                    document.getElementById('stockInicial').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockInicial').setAttribute('class', 'form-control is-valid');
                }

                if (this.item.stockMinimo < 0) {
                    this.errors.stockMinimo = 'El stock no puede ser negativo';
                    document.getElementById('stockMinimo').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockMinimo').setAttribute('class', 'form-control is-valid');
                }

                if (this.item.stockMaximo < 0) {
                    this.errors.stockMaximo = 'El stock no puede ser negativo';
                    document.getElementById('stockMaximo').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockMaximo').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.categoria) {
                    this.errors.categoria = 'Este campo es obligatorio';
                    document.getElementById('categoria').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('categoria').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.tipoVenta) {
                    this.errors.tipoVenta = 'Este campo es obligatorio';
                    document.getElementById('tipoVenta').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('tipoVenta').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.proveedor) {
                    this.errors.proveedor = 'Este campo es obligatorio';
                    document.getElementById('proveedor').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('proveedor').setAttribute('class', 'form-control is-valid');
                }

                if (!this.item.unidad) {
                    this.errors.unidad = 'Este campo es obligatorio';
                    document.getElementById('unidad').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('unidad').setAttribute('class', 'form-control is-valid');
                }

                this.validateProductoname();
                this.validateDate(this.item.fechaVencimiento);
            },
            validateEditForm() {

                this.editErrors = {};

                if (!this.editItem.nombre) {
                    this.editErrors.nombre = 'Este campo es obligatorio';
                    document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('nombreEdit').setAttribute('class', 'form-control is-valid');
                }

                if (!this.editItem.codigo) {
                    this.editErrors.codigo = 'Este campo es obligatorio';
                    document.getElementById('codigoEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('codigoEdit').setAttribute('class', 'form-control is-valid');
                }

                document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-valid');


                if (!this.editItem.precioCompra) {
                    this.editErrors.precioCompra = 'Este campo es obligatorio';
                    document.getElementById('precioCompraEdit').setAttribute('class', 'form-control is-invalid');
                } else if (this.editItem.precioCompra < 0) {
                    this.editErrors.precioCompra = 'El precio no puede ser negativo';
                    document.getElementById('precioCompraEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioCompraEdit').setAttribute('class', 'form-control is-valid');
                }

                if (!this.editItem.precioVenta) {
                    this.editErrors.precioVenta = 'Este campo es obligatorio';
                    document.getElementById('precioVentaEdit').setAttribute('class', 'form-control is-invalid');
                } else if (this.editItem.precioVenta < 0) {
                    this.editErrors.precioVenta = 'El precio no puede ser negativo';
                    document.getElementById('precioVentaEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioVentaEdit').setAttribute('class', 'form-control is-valid');
                }

                if (this.editItem.precioDescuento < 0) {
                    this.editErrors.precioDescuento = 'El precio no puede ser negativo';
                    document.getElementById('precioDescuentoEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioDescuentoEdit').setAttribute('class', 'form-control is-valid');
                }

                if (this.editItem.precioEspecial < 0) {
                    this.editErrors.precioEspecial = 'El precio no puede ser negativo';
                    document.getElementById('precioEspecialEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('precioEspecialEdit').setAttribute('class', 'form-control is-valid');
                }

                if (!this.editItem.stock) {
                    this.editErrors.stock = 'Este campo es obligatorio';
                    document.getElementById('stockEdit').setAttribute('class', 'form-control is-invalid');
                } else if (this.editItem.stock < 0) {
                    this.editErrors.stock = 'El stock no puede ser negativo';
                    document.getElementById('stockEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockEdit').setAttribute('class', 'form-control is-valid');
                }

                if (!this.editItem.stockInicial) {
                    this.editErrors.stockInicial = 'Este campo es obligatorio';
                    document.getElementById('stockInicialEdit').setAttribute('class', 'form-control is-invalid');
                } else if (this.editItem.stockInicial < 0) {
                    this.editErrors.stockInicial = 'El stock no puede ser negativo';
                    document.getElementById('stockInicialEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockInicialEdit').setAttribute('class', 'form-control is-valid');
                }

                if (this.editItem.stockMinimo < 0) {
                    this.editErrors.stockMinimo = 'El stock no puede ser negativo';
                    document.getElementById('stockMinimoEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockMinimoEdit').setAttribute('class', 'form-control is-valid');
                }

                if (this.editItem.stockMaximo < 0) {
                    this.editErrors.stockMaximo = 'El stock no puede ser negativo';
                    document.getElementById('stockMaximoEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('stockMaximoEdit').setAttribute('class', 'form-control is-valid');
                }


                document.getElementById('categoriaEdit').setAttribute('class', 'form-control is-valid');
                document.getElementById('tipoVentaEdit').setAttribute('class', 'form-control is-valid');
                document.getElementById('proveedorEdit').setAttribute('class', 'form-control is-valid');
                document.getElementById('unidadEdit').setAttribute('class', 'form-control is-valid');
                document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');

                this.validateEditProductoname();
                //this.validateEditDate(this.editItem.fechaVencimiento);
            },
            validateProductoname() {


                //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                let regex = /^[ a-zA-Z0-9.]{3,}$/;

                if (!regex.test(this.item.nombre)) {

                    document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                    this.errors.nombre =
                    'El producto debe tener al menos 3 caracteres y no contener caracteres especiales';
                }

                for (let i = 0; i < this.productos.length; i++) {
                    if (this.productos[i].codigo == this.item.codigo) {
                        this.errors.codigo = 'El producto ya existe';
                        document.getElementById('codigo').setAttribute('class', 'form-control is-invalid');
                    }
                }
            },
            validateEditProductoname() {

                //this.editErrors = {};

                let regex = /^[ a-zA-Z0-9.]{3,}$/;

                if (!regex.test(this.editItem.nombre)) {
                    document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                    this.editErrors.nombre =
                        'El producto debe tener al menos 3 caracteres y no contener espacios especiales';
                }

                //Eliminar del array el producto que se esta editando
                this.productos = this.productos.filter(producto => producto.id != this.editItem.id);

                //recorrer this.productos
                for (let i = 0; i < this.productos.length; i++) {
                    if (this.productos[i].codigo == this.editItem.codigo) {
                        this.editErrors.codigo = 'El producto ya existe';
                        document.getElementById('codigoEdit').setAttribute('class', 'form-control is-invalid');
                    }
                }

            },
            validateEditDate(date) {

                if (!date) {
                    document.getElementById('fechaVencimientoEdit').setAttribute('class', 'form-control is-valid');
                    return;
                }

                let fecha = new Date(date);
                let hoy = new Date();

                if (fecha < hoy) {
                    this.editErrors.fechaVencimiento =
                        'La fecha de vencimiento no puede ser menor o igual a la fecha actual';
                    document.getElementById('fechaVencimientoEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('fechaVencimientoEdit').setAttribute('class', 'form-control is-valid');
                }

            },
            validateDate(date) {

                if (!date) {
                    document.getElementById('fechaVencimiento').setAttribute('class', 'form-control is-valid');
                    return;
                }

                let fecha = new Date(date);
                let hoy = new Date();

                if (fecha < hoy) {
                    this.errors.fechaVencimiento =
                        'La fecha de vencimiento no puede ser menor o igual a la fecha actual';
                    document.getElementById('fechaVencimiento').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('fechaVencimiento').setAttribute('class', 'form-control is-valid');
                }

            },
            //Parse
            parseDouble(value) {
                if (value) {
                    return parseFloat(value).toFixed(2);
                }
            },
            formatDate(date) {

                if (!date) {
                    return;
                }

                let dateObj = new Date(date);
                let month = dateObj.getUTCMonth() + 1;
                let day = dateObj.getUTCDate();
                let year = dateObj.getUTCFullYear();

                return day + "/" + month + "/" + year;

            },
            //Paginacion
            pageMinus() {
                if (this.page > 1) {
                    this.page--;
                    this.getAllProductos();
                }
            },
            pagePlus() {
                if (this.page < this.totalPages) {
                    this.page++;
                    this.getAllProductos();
                }
            },
            specificPage(page) {
                this.page = page;
                this.getAllProductos();
            },
            changePerPage() {
                this.page = 1;
                this.getAllProductos();
            },
            //Lotes
            sendLotes(producto) {

                this.validateLote();

                if (Object.keys(this.lotesError).length > 0) {
                    return;
                }

                axios({
                    method: 'post',
                    url: '/lotes/update/' + producto,
                    data: {
                        lotes: this.lotes
                    }
                }).then(response => {

                    if (response.data.success) {
                        swal.fire({
                            title: 'Lotes actualizados',
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

                    swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error al actualizar los lotes',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });

                }).finally(() => {
                    document.getElementById('cancelLotesButton').click();
                    //limpiar
                    this.cleanForm();
                    //Recargar productos
                    this.getAllProductos();
                })
            },
            toggleEdit() {
                this.editMode = !this.editMode;
            },
            validateLote() {
                this.lotesError = {};

                for (let i = 0; i < this.lotes.length; i++) {

                    if (this.lotes[i].fechaVencimiento) {

                        if (new Date(this.lotes[i].fechaVencimiento) < new Date()) {
                            this.lotesError.fechaVencimiento =
                                'La fecha de vencimiento no puede ser menor o igual a la fecha actual';
                            document.getElementById('fechaVencimientoLote').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('fechaVencimientoLote').setAttribute('class', 'form-control is-valid');
                        }

                    } else {
                        document.getElementById('fechaVencimientoLote').setAttribute('class', 'form-control is-valid');
                    }

                }
            },
            //Limpiar formulario y busqueda
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
                this.searchError = '';
                this.search = null;
                this.lotes = [];
                this.lotesError = {};
                this.editMode = false;
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

                document.getElementById('nombre').setAttribute('class', 'form-control');
                document.getElementById('codigo').setAttribute('class', 'form-control');
                document.getElementById('descripcion').setAttribute('class', 'form-control');
                document.getElementById('precioCompra').setAttribute('class', 'form-control');
                document.getElementById('precioVenta').setAttribute('class', 'form-control');
                document.getElementById('precioDescuento').setAttribute('class', 'form-control');
                document.getElementById('precioEspecial').setAttribute('class', 'form-control');
                document.getElementById('fechaVencimiento').setAttribute('class', 'form-control');
                document.getElementById('stock').setAttribute('class', 'form-control');
                document.getElementById('stockInicial').setAttribute('class', 'form-control');
                document.getElementById('stockMinimo').setAttribute('class', 'form-control');
                document.getElementById('stockMaximo').setAttribute('class', 'form-control');
                document.getElementById('categoria').setAttribute('class', 'form-control');
                document.getElementById('tipoVenta').setAttribute('class', 'form-control');
                document.getElementById('proveedor').setAttribute('class', 'form-control');
                document.getElementById('unidad').setAttribute('class', 'form-control');

                document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                document.getElementById('codigoEdit').setAttribute('class', 'form-control');
                document.getElementById('descripcionEdit').setAttribute('class', 'form-control');
                document.getElementById('precioCompraEdit').setAttribute('class', 'form-control');
                document.getElementById('precioVentaEdit').setAttribute('class', 'form-control');
                document.getElementById('precioDescuentoEdit').setAttribute('class', 'form-control');
                document.getElementById('precioEspecialEdit').setAttribute('class', 'form-control');
                document.getElementById('fechaVencimientoEdit').setAttribute('class', 'form-control');
                document.getElementById('stockEdit').setAttribute('class', 'form-control');
                document.getElementById('stockInicialEdit').setAttribute('class', 'form-control');
                document.getElementById('stockMinimoEdit').setAttribute('class', 'form-control');
                document.getElementById('stockMaximoEdit').setAttribute('class', 'form-control');
                document.getElementById('categoriaEdit').setAttribute('class', 'form-control');
                document.getElementById('tipoVentaEdit').setAttribute('class', 'form-control');
                document.getElementById('proveedorEdit').setAttribute('class', 'form-control');
                document.getElementById('unidadEdit').setAttribute('class', 'form-control');
                document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                if (this.searchProductos.length == 0) {
                    this.productos = [];
                    this.searchProductos = [];
                    this.getAllProductos();
                } else {
                    this.productos = this.searchProductos;
                }
            },
            cleanSearch() {
                this.search = null;
                this.getAllProductos();
                this.searchError = '';
            },
            //Obtener recursos
            async getAllProductos() {
                axios({
                    method: 'get',
                    url: '/allProductos',
                    params: {
                        page: this.page,
                        per_page: this.per_page,
                        //onlyActive: true,
                        search: this.search,
                        sortBy: 'estado',
                    }
                }).then(response => {

                    this.productoError = response.data.error;

                    if (this.productoError) {
                        this.loading = false;
                        this.productos = [];
                        this.searchProductos = [];
                        return;
                    }

                    this.loading = false;
                    this.productos = response.data.data;

                    this.searchProductos = response.data.data;

                    this.total = response.data.total;
                    this.totalPages = response.data.last_page;
                    if (this.page > this.totalPages) {
                        this.page = 1;
                        this.getAllProductos();
                    } else {
                        this.page = response.data.current_page;
                    }
                    this.per_page = response.data.per_page;
                    this.nextPageUrl = response.data.next_page_url;
                    this.prevPageUrl = response.data.prev_page_url;


                }).catch(error => {
                    this.loading = false;
                    swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error al obtener los productos',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                })
            },
            async getAllEstados() {

                try {
                    let response = await fetch('/allEstados');
                    let data = await response.json();
                    this.estados = data;
                } catch (error) {

                }


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
            },
            async getAllLotes($producto) {
                document.getElementById('getAllLotesModalBtn').click();
                this.loading = true;
                let response = await fetch('/getLotes/' + $producto.id);
                let data = await response.json();
                this.lotes = data;
                this.loading = false;
            },

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
