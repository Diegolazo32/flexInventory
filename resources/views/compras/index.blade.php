@extends('Layouts.Navigation')

@section('title', 'Compras')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Compras</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end" style="display: flex; gap: 5px;">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#infoProductoModal" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-info"></i>
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#nuevaCompraModal" style="height: 40px;">
                            <i class="fas fa-plus"></i>
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
                                <input type="text" class="form-control" name="search" placeholder="Buscar por codigo"
                                    v-model="searchCompra" id="searchCompra" @keyup.enter="searchCompraFn">
                                <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="searchCompraFn"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="searchCompra" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearchMain"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de compras -->
            <div class="row">
                <div class="card-body">

                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="comprasError" class="alert alert-danger" role="alert">
                        <h3>@{{ comprasError }}</h3>
                    </div>

                    <div v-if="compras.length > 0 && !loading" class="table-responsive">
                        <table ref="table" class="table table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Fecha de compra</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Ver detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for="compra in compras" :key="compra.id">
                                    <td>@{{ compra.codigo }}</td>
                                    <td>$@{{ parseDouble(compra.total) }}</td>
                                    <td>@{{ parseDate(compra.fecha) }}</td>
                                    <td>
                                        <span v-if="compra.estado == 5" class="badge bg-warning">Pendiente</span>
                                        <span v-if="compra.estado == 6" class="badge bg-success">Pagado</span>
                                        <span v-if="compra.estado == 7" class="badge bg-danger">Anulado</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            v-if="compra.estado == 5" id="EditCompraModalBtn"
                                            data-bs-target="#EditCompraModal" @click="getCompraDetails(compra.id)">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" v-else
                                            id="compraDetallesModalBtn" data-bs-target="#compraDetallesModal"
                                            @click="getCompraDetails(compra.id)">
                                            <i class="fas fa-eye"></i>
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

        <!-- Nueva compra Modal -->
        <div class="modal fade" id="nuevaCompraModal" tabindex="-1" aria-labelledby="nuevaCompraLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down"
                style="min-width: 95%;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearProductoModalLabel">Registrar nueva compra </h1>
                    </div>
                    <div class="modal-body row" style="display: flex; justify-content: center; gap: 10px;">

                        <div class="card col-lg-8" style="margin-bottom: 10px; padding: 0%;">
                            <!-- Buscador -->
                            <div class="card-header">
                                <div class="row" style="max-height: 38px;">
                                    <div class="col-6">
                                        <input :disabled="productos <= 0" type="text" class="form-control"
                                            placeholder="Buscar producto" v-model="busquedaProducto"
                                            @keyup="searchProductoFn">
                                        <ul v-if="results.length > 0" class="list-group">
                                            <li v-for="result in results" class="list-group-item"
                                                @click="selectResult(result)" style="cursor: pointer;"
                                                @keyup.enter="selectResult(result)" tabindex="0">@{{ result.codigo }}
                                                -
                                                @{{ result.nombre }} - @{{ result.unidad }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6" v-if="compras.length > 0"
                                        style="display: flex; justify-content: flex-end; max-height: 37px;">
                                        <button type="button" class="btn btn-outline-primary" @click="cleanForm">Limpiar
                                            tabla</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Tabla de productos -->


                            <div v-if="productos.error" class="alert alert-danger" role="alert">
                                <h3>@{{ productos.error }}</h3>
                            </div>

                            <div v-if="productos.length == 0 && !loading" class="alert alert-warning" role="alert">
                                <h3>No hay productos registrados</h3>
                            </div>

                            <div class="table-responsive">
                                <table ref="table" class="table table-hover" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th scope="col">Codigo</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Stock maximo</th>
                                            <th scope="col">Precio de compra</th>
                                            <th scope="col">Proveedor</th>
                                            <th scope="col">Fecha de vencimiento</th>
                                            <th scope="col">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- vue foreach -->
                                        <tr v-for="(producto, index) in productosCompra" :key="producto.id">
                                            <td>@{{ producto.codigo }}</td>
                                            <td>@{{ producto.nombre }}</td>
                                            <td>
                                                <input type="number" class="form-control" v-model="producto.cantidad"
                                                    @change="calcularTotal" min="1" :id="'cantidad' + producto.id">
                                            </td>
                                            <td><input type="number" class="form-control" v-model="producto.stockMaximo"
                                                    disabled></td>
                                            <td>
                                                <input type="number" class="form-control" v-model="producto.precio"
                                                    @change="calcularTotal" min="0.01" step="0.01">
                                            </td>
                                            <td>
                                                <select class="form-select" v-model="producto.proveedor">
                                                    <option v-for="proveedor in proveedores" :key="proveedor.id"
                                                        :value="proveedor.id">@{{ proveedor.nombre }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="fechaVencimiento"
                                                    v-model="producto.fechaVencimiento">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger"
                                                    @click="deleteProduct(index)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class=" card col-lg-3" style="margin-bottom: 10px; padding: 0%;">
                            <div class="card-header">
                                <h3>Factura</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12"
                                        style="display: flex; justify-content: center; font-size: 48px; font-family: math; margin-bottom: 10px;">
                                        $@{{ totalCompra ?? 0 }}
                                    </div>
                                    <!-- Codigo de compra -->
                                    <div class="col-12">
                                        <label for="codigoCompra">Codigo de compra</label>
                                        <input type="text" class="form-control" v-model="codigoCompra"
                                            placeholder="Codigo o numero de Factura" style="margin-bottom: 10px;"
                                            maxlength="24">
                                    </div>
                                    <!-- Fecha de compra -->
                                    <div class="col-12">
                                        <label for="fechaCompra">Fecha de compra</label>
                                        <input type="date" class="form-control" v-model="fechaCompra"
                                            placeholder="Fecha de compra" style="margin-bottom: 10px;">
                                    </div>

                                    <!-- Metodo de pago -->
                                    <div class="col-12">
                                        <label for="tipoPago">Forma de pago</label>
                                        <select class="form-select" name="tipoPago" v-model="tipoPago">
                                            <option value="1">Efectivo</option>
                                            <option value="2">Tarjeta de débito o crédito</option>
                                            <option value="3">Transferencia Bancaria</option>
                                            <option value="4">Pendiente de pago</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="estado">Estado</label>
                                        <select class="form-select" name="estado" v-model="estado">
                                            <option value="6"> Pagado </option>
                                            <option value="5">Pendiente</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="observaciones">Observaciones</label>
                                        <textarea class="form-control" name="observaciones" v-model="observaciones" placeholder="Observaciones"
                                            style="margin-bottom: 10px;"></textarea>

                                    </div>
                                </div>
                            </div>
                        </div>
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

        <!--Info modal-->
        <div class="modal fade" id="infoProductoModal" tabindex="-1" aria-labelledby="infoProductoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-3" id="infoProductoModalLabel">¿Como realizar una compra?</h1>
                    </div>
                    <div class="modal-body">
                        <p>Para realizar una compra, seleccione el producto que desea comprar y la cantidad que desea
                            comprar. Luego seleccione el proveedor de ese producto, a continuacion puede ajustar el precio
                            de compra y finalmente presione el boton de guardar.</p>
                        <h5>¿El precio de compra registrado es diferente al que le ofrece su proveedor?</h5>
                        <p>No se preocupe, puede cambiarlo directamente en la tabla de productos, solo debe seleccionar el
                            producto y cambiar el precio de compra. Esto hara que la compra se registre con el nuevo precio
                            y el precio del producto se actualizara al nuevo ingresado</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelShowButton"
                            @click="cleanForm">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- compraDetalles modal -->
        <div class="modal fade" id="compraDetallesModal" tabindex="-1" aria-labelledby="compraDetallesModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex; justify-content: space-evenly; align-items: center;">
                        <h3 class="modal-title">Codigo: @{{ compraDetalles.codigo ?? '-' }}</h3>
                        <h3 class="modal-title">Fecha: @{{ compraDetalles.fecha ? parseDate(compraDetalles.fecha) : '-' }}</h3>
                        <h3 v-if="compraDetalles.estado == 5"><span class="badge bg-warning">Pendiente</span></h3>
                        <h3 v-if="compraDetalles.estado == 6"><span class="badge bg-success">Pagado</span></h3>
                        <h3 v-if="compraDetalles.estado == 7"><span class="badge bg-danger">Anulado</span></h3>
                        <h3 class="modal-title">Total: $@{{ parseDouble(compraDetalles.total) ?? '-' }}</h3>
                    </div>
                    <div class="modal-body">

                        <div v-if="loadingDetails" role="alert"
                            style="display: flex; justify-content: center; align-items: center;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive" v-else-if="productosDetalles.length > 0 && !loading">
                            <table ref="table" class="table table-hover table-striped" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio de compra</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Fecha de vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="producto in productosDetalles" :key="producto.id">
                                        <td>@{{ producto.codigo ?? '-' }}</td>
                                        <td>@{{ producto.nombre ?? '-' }}</td>
                                        <td>@{{ producto.cantidad ?? '-' }}</td>
                                        <td>$@{{ producto.precio ? parseDouble(producto.precio) : '-' }}</td>
                                        <td>@{{ producto.proveedor ?? '-' }}</td>
                                        <td>@{{ producto.fechaVencimiento ? parseDate(producto.fechaVencimiento) : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelShowButton"
                            @click="cleanForm">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Compra modal -->
        <div class="modal fade" id="EditCompraModal" tabindex="-1" aria-labelledby="EditCompraModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex; justify-content: space-evenly; align-items: center;">
                        <h3 class="modal-title">Codigo: @{{ compraDetalles.codigo ?? '-' }}</h3>
                        <h3 class="modal-title">Fecha: @{{ compraDetalles.fecha ? parseDate(compraDetalles.fecha) : '-' }}</h3>
                        <h3 v-if="compraDetalles.estado == 5"><span class="badge bg-warning">Pendiente</span></h3>
                        <h3 v-if="compraDetalles.estado == 6"><span class="badge bg-success">Pagado</span></h3>
                        <h3 v-if="compraDetalles.estado == 7"><span class="badge bg-danger">Anulado</span></h3>
                        <h3 class="modal-title">Total: $@{{ parseDouble(compraDetalles.total) ?? '-' }}</h3>
                    </div>
                    <div class="modal-body">

                        <div v-if="loadingDetails" role="alert"
                            style="display: flex; justify-content: center; align-items: center;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive" v-else-if="productosDetalles.length > 0 && !loading">
                            <table ref="table" class="table table-hover table-striped" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio de compra</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Fecha de vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="producto in productosDetalles" :key="producto.id">
                                        <td>@{{ producto.codigo ?? '-' }}</td>
                                        <td>@{{ producto.nombre ?? '-' }}</td>
                                        <td>@{{ producto.cantidad ?? '-' }}</td>
                                        <td>$@{{ producto.precio ? parseDouble(producto.precio) : '-' }}</td>
                                        <td>@{{ producto.proveedor ?? '-' }}</td>
                                        <td>@{{ producto.fechaVencimiento ? parseDate(producto.fechaVencimiento) : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelEditButton"
                            @click="cleanForm">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="anularButton"
                            @click="sendNullifyForm(compraDetalles)">Anular</button>
                        <button type="button" class="btn btn-success" @click="sendPayForm(compraDetalles)"
                            id="pagarButton">Pagar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                //paginacion
                page: 1,
                per_page: 10,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
                total: 0,

                //loading
                loading: true,
                loadingDetails: true,

                //compras
                compras: [],
                comprasError: '',
                searchCompra: '',

                //productos
                productos: [],
                busquedaProducto: '',
                searchProductos: [],
                productosError: '',

                //Proveedores
                proveedores: [],
                proveedoresError: '',
                searchProveedores: [],

                //unidades
                unidades: [],
                searchUnidades: [],
                unidadesError: '',

                //Busqueda
                searchError: '',

                //Nueva compra
                productosCompra: [],
                results: [],
                totalCompra: 0,
                codigoCompra: '',
                quantity: 1,
                nuevaCompraErrors: [],
                tipoPago: 1,
                estado: 6,
                observaciones: '',
                //Today's date
                fechaCompra: new Date().toISOString().substr(0, 10),

                //Detalles de compra
                compraDetalles: [],
                productosDetalles: [],

                //overstock
                overstock: [],

            },
            methods: {
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllCompras();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllCompras();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllCompras();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllCompras();
                },

                //Busqueda
                searchCompraFn() {
                    if (this.searchCompra) {
                        this.getAllCompras();
                    } else {
                        document.getElementById('searchCompra').setAttribute('class', 'form-control is-invalid');
                        this.searchError = 'El campo de busqueda esta vacio';
                    }
                },
                searchProductoFn() {

                    this.searchError = '';

                    //Si el campo empieza con un numero seguido de un * o x se considera el numero como la cantidad y se busca el producto
                    //Ejemplo: 5*producto o 5xproducto busca el producto y establece la cantidad en 5
                    if (this.busquedaProducto) {
                        let search = this.busquedaProducto.toLowerCase();
                        let productos = this.searchProductos;

                        if (search.includes('*') || search.includes('x')) {
                            this.quantity = search.split('*')[0];

                            //Nombre sera el resto de la cadena despues del * o x
                            let nombre = search.split('*')[1] || search.split('x')[1];

                            //Buscar producto
                            this.filtered = productos.filter(producto => {
                                return producto.nombre.toLowerCase().includes(search)
                            });

                            try {
                                this.filtered = productos.filter(producto => {
                                    return producto.nombre.toLowerCase().includes(nombre) ||
                                        producto.codigo.toLowerCase().includes(nombre)
                                });
                            } catch (error) {
                                swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Error',
                                    text: error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }

                            if (this.filtered.length == 0) {
                                this.results = ['No se encontraron resultados'];
                            }

                            if (this.filtered.length > 5) {
                                this.results = this.filtered.slice(0, 5);
                            } else {
                                this.results = this.filtered;
                            }

                        } else {



                            try {
                                this.filtered = productos.filter(producto => {
                                    return producto.nombre.toLowerCase().includes(search) ||
                                        producto.codigo.toLowerCase().includes(search)
                                });
                            } catch (error) {
                                swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Error',
                                    text: error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }

                            if (this.filtered.length == 0) {
                                this.results = ['No se encontraron resultados'];
                            }

                            if (this.filtered.length > 5) {
                                this.results = this.filtered.slice(0, 5);
                            } else {
                                this.results = this.filtered;
                            }
                        }

                        //Cambiar la unidad id por su nombre
                        this.results.forEach(result => {
                            let unidad = this.unidades.find(unidad => {
                                return unidad.id == result.unidad;
                            });

                            result.unidad = unidad.descripcion;
                        });



                    } else {
                        this.results = [];
                    }
                },

                //Nueva compra
                selectResult(result) {
                    this.results = [];
                    this.busquedaProducto = '';

                    let producto = {
                        id: result.id,
                        codigo: result.codigo,
                        nombre: result.nombre,
                        precio: result.precioCompra,
                        proveedor: result.proveedor,
                        cantidad: this.quantity,
                        observacion: '',
                        fechaVencimiento: '',
                        stockMaximo: result.stockMaximo,
                        stock: result.stock,
                    };

                    //chequear si ya esta el producto en el array
                    let index = this.productosCompra.findIndex(productoCompra => {
                        return productoCompra.id == producto.id;
                    });

                    if (index == -1) {
                        this.productosCompra.push(producto);
                    } else {
                        //Sumarle la cantidad a ese producto
                        $cantidad = this.productosCompra[index].cantidad;
                        $cantidad = parseInt($cantidad) + parseInt(this.quantity);
                        this.productosCompra[index].cantidad = $cantidad;
                    }

                    this.calcularTotal();
                },
                deleteProduct(index) {
                    this.productosCompra.splice(index, 1);
                    this.calcularTotal();
                },
                calcularTotal() {
                    let total = 0;
                    this.productosCompra.forEach(producto => {
                        total += producto.precio * producto.cantidad;
                    });
                    this.totalCompra = parseFloat(total).toFixed(2);
                },
                validateCompra() {

                    this.nuevaCompraErrors = [];
                    this.overstock = [];

                    if (this.productosCompra.length < 1) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'No se han agregado productos a la compra',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                        this.nuevaCompraErrors.push('No se han agregado productos a la compra');

                        return;
                    }

                    if (this.totalCompra < 1) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'El total de la compra no puede ser 0',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                        this.nuevaCompraErrors.push('El total de la compra no puede ser 0');

                        return;
                    }

                    if (this.codigoCompra == '') {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'El codigo de la compra no puede estar vacio',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                        this.nuevaCompraErrors.push('El codigo de la compra no puede estar vacio');

                        return;
                    }

                    //Verificar si algun producto esta comprando mas del stock maximo permitido
                    this.productosCompra.forEach(producto => {

                        let cantidad = 0;
                        cantidad = parseInt(producto.cantidad);
                        stockMax = parseInt(producto.stockMaximo);

                        if (cantidad > stockMax) {

                            this.overstock.push(producto);

                        }


                    });


                },

                //Limpieza
                cleanForm() {

                    //this.compras = []
                    //this.comprasError = ''
                    //this.searchCompra = ''
                    this.busquedaProducto = ''
                    this.proveedoresError = ''

                    //Busqueda
                    this.searchError = ''

                    //Nueva compra
                    this.productosCompra = []
                    this.results = []
                    this.totalCompra = 0
                    this.codigoCompra = ''
                    this.quantity = 1
                    this.nuevaCompraErrors = []
                    this.tipoPago = 1
                    this.estado = 6
                    this.observaciones = ''
                    //Today's date
                    this.fechaCompra = new Date().toISOString().substr(0, 10)

                    //Detalles
                    this.compraDetalles = []
                    this.productosDetalles = []

                },

                parseDouble(value) {
                    if (value) {
                        return parseFloat(value).toFixed(2);
                    }
                    return 0;
                },
                parseDate(date) {


                    if (date == null) {
                        return '-';
                    }

                    let dateObj = new Date(date);
                    let month = dateObj.getUTCMonth() + 1;
                    let day = dateObj.getUTCDate();
                    let year = dateObj.getUTCFullYear();

                    return day + "/" + month + "/" + year;

                },
                //Obtener recursos
                async getAllCompras() {
                    axios({
                        method: 'get',
                        url: '/allCompras',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            //onlyActive: true,
                            search: this.searchCompra,
                        },
                    }).then(response => {

                        if (response.data.error) {
                            this.comprasError = response.data.error;
                            this.compras = [];
                            return;
                        }

                        this.compras = response.data.data;

                        //Paginacion
                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;
                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.getAllCompras();
                        } else {
                            this.page = response.data.current_page;
                        }
                        this.per_page = response.data.per_page;
                        this.nextPageUrl = response.data.next_page_url;
                        this.prevPageUrl = response.data.prev_page_url;

                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las compras',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {
                        this.loading = false;
                    });
                },
                async getAllProductos() {
                    axios({
                        method: 'get',
                        url: '/allProductos',
                        params: {
                            //page: this.page,
                            //per_page: this.per_page,
                            //onlyActive: false,
                            //search: this.busquedaProducto,
                        },
                    }).then(response => {

                        this.productosError = response.data.error;

                        if (response.data.error) {
                            this.productos = [];
                            return;
                        }

                        this.productos = response.data;
                        this.searchProductos = response.data;

                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los productos',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {
                        this.loading = false;
                    });
                },
                async getAllProveedores() {
                    axios({
                        method: 'get',
                        url: '/allProveedores',
                        params: {
                            //page: this.page,
                            //per_page: this.per_page,
                            onlyActive: true,
                            //search: this.busquedaProducto,
                        },
                    }).then(response => {

                        this.proveedoresError = response.data.error;

                        if (response.data.error) {
                            this.proveedores = [];
                            return;
                        }

                        this.proveedores = response.data;
                        this.searchProveedores = response.data;

                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los proveedores',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                },
                async getCompraDetails(id) {

                    this.loadingDetails = true;

                    axios({
                        method: 'get',
                        url: '/getCompraDetails/' + id,
                    }).then(response => {

                        if (response.data.error) {
                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: response.data.error,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                            return;
                        }

                        this.compraDetalles = response.data.compra;
                        this.productosDetalles = response.data.productos;


                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los detalles de la compra',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {
                        this.loadingDetails = false;
                    });

                },
                //Envio de formularios
                sendForm() {

                    document.getElementById('SubmitForm').disabled = true;
                    document.getElementById('SubmitForm').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> Guardando';

                    document.getElementById('cancelButton').disabled = true;

                    this.validateCompra();

                    if (this.nuevaCompraErrors.length > 0) {

                        document.getElementById('SubmitForm').disabled = false;
                        document.getElementById('SubmitForm').innerHTML = 'Guardar';
                        document.getElementById('cancelButton').disabled = false;

                        return;
                    }

                    if (this.overstock.length > 0) {
                        let overstock = '';
                        this.overstock.forEach(producto => {
                            overstock += producto.nombre + ', ';
                        });

                        swal.fire({
                            title: 'Overstock',
                            text: 'Algunos productos estan siendo comprados en cantidades mayores a su stock maximo: ' +
                                overstock + ' ¿Desea continuar?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Si',
                            cancelButtonText: 'No',
                        }).then((result) => {

                            //If yes, continue with the rest of the function
                            if (result.isConfirmed) {

                                if (this.nuevaCompraErrors.length > 0) {

                                    document.getElementById('SubmitForm').disabled = false;
                                    document.getElementById('SubmitForm').innerHTML = 'Guardar';
                                    document.getElementById('cancelButton').disabled = false;

                                    return;
                                }

                                let data = {
                                    productos: this.productosCompra,
                                    total: this.totalCompra,
                                    codigo: this.codigoCompra,
                                    fecha: this.fechaCompra,
                                    tipoPago: this.tipoPago,
                                    estado: this.estado,
                                    observaciones: this.observaciones,
                                };

                                axios({
                                    method: 'post',
                                    url: '/compras/store',
                                    data: data,
                                }).then(response => {
                                    if (response.data.error) {
                                        swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            title: 'Error',
                                            text: response.data.error,
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar'
                                        });

                                        this.cleanForm();
                                        this.getAllCompras();

                                        return;
                                    }

                                    swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        title: 'Compra registrada',
                                        text: response.data.success,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar'
                                    });

                                    this.comprasError = '';

                                }).catch(error => {
                                    swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        title: 'Error',
                                        text: 'Ha ocurrido un error al registrar la compra',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });

                                }).finally(() => {

                                    document.getElementById('SubmitForm').disabled = false;
                                    document.getElementById('SubmitForm').innerHTML = 'Guardar';

                                    document.getElementById('cancelButton').disabled = false;

                                    document.getElementById('cancelButton').click();

                                    this.cleanForm();
                                    this.getAllCompras();
                                });
                            }

                        });


                    } else {

                        if (this.nuevaCompraErrors.length > 0) {

                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('SubmitForm').innerHTML = 'Guardar';
                            document.getElementById('cancelButton').disabled = false;

                            return;
                        }

                        let data = {
                            productos: this.productosCompra,
                            total: this.totalCompra,
                            codigo: this.codigoCompra,
                            fecha: this.fechaCompra,
                            tipoPago: this.tipoPago,
                            estado: this.estado,
                            observaciones: this.observaciones,
                        };

                        axios({
                            method: 'post',
                            url: '/compras/store',
                            data: data,
                        }).then(response => {
                            if (response.data.error) {
                                swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Error',
                                    text: response.data.error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });

                                this.cleanForm();
                                this.getAllCompras();

                                return;
                            }

                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Compra registrada',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });

                            this.comprasError = '';

                        }).catch(error => {
                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al registrar la compra',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('SubmitForm').innerHTML = 'Guardar';

                            document.getElementById('cancelButton').disabled = false;

                            document.getElementById('cancelButton').click();

                            this.cleanForm();
                            this.getAllCompras();
                            this.getAllProductos();
                        });
                    }

                },
                async getAllUnidades() {
                    axios({
                        method: 'get',
                        url: '/allUnidades',
                    }).then(response => {

                        this.unidades = response.data;

                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las unidades',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                },

                async sendPayForm(compra) {

                    //Disable buttons
                    document.getElementById('pagarButton').disabled = true;
                    document.getElementById('anularButton').disabled = true;
                    document.getElementById('cancelEditButton').disabled = true;

                    //Animar pagarButton
                    document.getElementById('pagarButton').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> Pagando...';


                    axios({
                        method: 'post',
                        url: '/payCompra/' + compra.id,
                    }).then(response => {

                        if (response.data.success) {
                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Compra pagada',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: response.data.error,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }


                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las unidades',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {

                        document.getElementById('pagarButton').innerHTML =
                            'Pagar';

                        document.getElementById('pagarButton').disabled = false;
                        document.getElementById('anularButton').disabled = false;
                        document.getElementById('cancelEditButton').disabled = false;

                        document.getElementById('cancelEditButton').click();

                        this.cleanForm();
                        this.getAllCompras();
                        this.getAllProductos();
                    });
                },
                async sendNullifyForm(compra) {

                    //Disable buttons
                    document.getElementById('pagarButton').disabled = true;
                    document.getElementById('anularButton').disabled = true;
                    document.getElementById('cancelEditButton').disabled = true;

                    //Animar pagarButton
                    document.getElementById('anularButton').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> Anulando...';

                    axios({
                        method: 'post',
                        url: '/nullifyCompra/' + compra.id,
                    }).then(response => {


                        if (response.data.success) {
                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Compra anulada',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: response.data.error,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }


                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las unidades',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {

                        //Animar pagarButton
                        document.getElementById('anularButton').innerHTML =
                            'Anular';

                        document.getElementById('pagarButton').disabled = false;
                        document.getElementById('anularButton').disabled = false;
                        document.getElementById('cancelEditButton').disabled = false;

                        document.getElementById('cancelEditButton').click();

                        this.cleanForm();
                        this.getAllCompras();
                        this.getAllProductos();
                    });

                },

            },
            mounted() {
                this.getAllProveedores();
                this.getAllUnidades();
                this.getAllCompras();
                this.getAllProductos();
            }
        });
    </script>
@endsection
