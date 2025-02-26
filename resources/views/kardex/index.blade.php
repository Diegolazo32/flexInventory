@extends('Layouts.Navigation')

@section('title', 'Kardex')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Kardex</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end" style="display: flex; gap: 5px;">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#infoProductoModal" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-info"></i>
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crearProductoModal" style="height: 40px;">
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
                                <input type="text" class="form-control" name="search"
                                    placeholder="Buscar por codigo o nombre" v-model="searchMainProductos">
                                <small class="text-danger" v-if="searchError">@{{ searchError }}</small>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="searchProductoFn"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="searchMainProductos" class="btn btn-primary"
                                    style="height: 40px; max-height: 40px;" @click="cleanSearchMain"><i
                                        class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de productos -->
            <div class="row">
                <div class="card-body">

                    <div class="table-responsive">
                        <table ref="table" class="table table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Saldo Inicial</th>
                                    <th scope="col">Entradas</th>
                                    <th scope="col">Salidas</th>
                                    <th scope="col">Saldo Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading" role="alert" id="loading">
                                    <td colspan="6">
                                        <div v-if="loading" role="alert"
                                            style="display: flex; justify-content: center; align-items: center;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- vue foreach -->
                                <tr v-else v-for="producto in mainProductos" :key="producto.id">
                                    <td>@{{ producto.codigo }}</td>
                                    <td>@{{ producto.nombre }}</td>
                                    <td>@{{ producto.stockInicial }}</td>
                                    <td style="color:green; font-weight:bold;">@{{ getEntradas(producto.id) }}</td>
                                    <td style="color: red; font-weight:bold;">@{{ getSalidas(producto.id) }}</td>
                                    <td style="font-weight:bold;">@{{ getTotal(producto.id) }}</td>
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
                        <h1 class="modal-title fs-5" id="crearProductoModalLabel">Realizar movimiento </h1>
                        <small class="text-muted">Seleccione los productos para realizar un movimiento</small>
                        <br>
                        <small class="text-muted">Si se deja en blanco la obervacion se guardara
                            como "Movimiento de stock"</small>
                    </div>
                    <div class="modal-body">

                        <div class="card">
                            <!-- Buscador -->
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <input :disabled="productos < 1" type="text" class="form-control"
                                            placeholder="Buscar producto" v-model="search" @keyup="searchFn">
                                        <ul v-if="results.length > 0" class="list-group">
                                            <li v-for="result in results" class="list-group-item"
                                                @click="selectResult(result)" style="cursor: pointer;"
                                                @keyup.enter="selectResult(result)" tabindex="0">@{{ result.codigo }}
                                                -
                                                @{{ result.nombre }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-6" v-if="kardex.length > 0"
                                        style="display: flex; justify-content: flex-end;">
                                        <button type="button" class="btn btn-outline-primary" @click="cleanForm">Limpiar
                                            tabla</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Tabla de productos -->
                            <div class="row">
                                <div class="card-body">


                                    <div v-if="productos.error" class="alert alert-danger" role="alert">
                                        <h3>@{{ productos.error }}</h3>
                                    </div>

                                    <div v-if="productos.length == 0 && !loading" class="alert alert-warning"
                                        role="alert">
                                        <h3>No hay productos registrados</h3>
                                    </div>

                                    <div class="table-responsive">
                                        <table ref="table" class="table table-hover" style="text-align: center;">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Codigo</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Accion</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Observacion</th>
                                                    <th scope="col">Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <div v-if="loading" role="alert"
                                                    style="display: flex; justify-content: center; align-items: center;">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                                <!-- vue foreach -->
                                                <tr v-else v-for="kard in kardex" :key="kard.id">
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            :id="'codigo' + kard.id" name="codigo" placeholder="Codigo"
                                                            v-model="kard.item.codigo" disabled>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            :id="'nombre' + kard.id" name="nombre" placeholder="Nombre"
                                                            v-model="kard.producto" disabled>
                                                    </td>
                                                    <td>
                                                        <select class="form-select" :id="'accion' + kard.id"
                                                            name="accion" v-model="kard.accion" required>
                                                            <option v-for="accion in acciones" :key="accion.id"
                                                                :value="accion.id">
                                                                @{{ accion.descripcion }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control"
                                                            :id="'cantidad' + kard.id" name="cantidad"
                                                            placeholder="Cantidad" v-model="kard.cantidad" step="1"
                                                            min="0" maxlength="6" :max="kard.item.stock"
                                                            @blur="validateMinStock(kard)">
                                                        <small :id="'errorCantidad' + kard.id" class="text-danger"
                                                            v-if="errors.cantidad">La cantidad no puede ser mayor al
                                                            stock</small>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            :id="'observacion' + kard.id" name="observacion"
                                                            placeholder="Observacion" v-model="kard.observacion">
                                                        <small class="text-danger" :id="'errorObservacion' + kard.id"
                                                            v-if="errors.observacion"></small>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger"
                                                            @click="deleteKardex(kard.id)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                        <h1 class="modal-title fs-5" id="infoProductoModalLabel">Kardex</h1>
                    </div>
                    <div class="modal-body" style="text-align: justify;">
                        <h4>¿Como funciona el Kardex?</h4>
                        <p>El Kardex es un sistema de control de inventarios que permite llevar un registro de las
                            entradas y salidas de productos en un almacén. El Kardex se puede llevar de forma manual o
                            automatizada, y es una herramienta fundamental para la gestión de inventarios.</p>
                        <p> Primero seleccione un producto y agreguelo al kardex, a continuacion establezca la accion
                            que desea realizar, ya sea una entrada o una salida de producto, luego ingrese la cantidad y
                            finalmente presione el boton de guardar.</p>
                        <p>El sistema se encargara de realizar la operacion correspondiente y actualizar el stock del
                            producto.</p>
                        <h4>¿Que pasa si un producto se queda sin stock por un movimiento de Kardex?</h4>
                        <p>Primero se evaluara si hay suficiente stock para realizar la accion, si es asi, se completa el
                            movimiento y luego se desactivara el producto automaticamente cuando este se quede a 0, lo que
                            hara que no pueda realizar mas acciones con el producto hasta que ingrese mas producto a traves
                            de una compra.</p>
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
                //Variables de modal de movimientos
                search: null,
                productos: [],
                searchProductos: [],

                //Variables de tabla de kardex
                searchMainProductos: null,
                mainProductos: [],
                searchMainProd: [],

                //Variables
                errors: {},
                filtered: [],
                estados: [],
                categorias: [],
                tipoVentas: [],
                proveedores: [],
                unidades: [],
                searchError: '',
                loading: true,
                results: [],
                kardex: [],
                kardexItem: {
                    id: null,
                    item: null,
                    producto: null,
                    accion: null,
                    cantidad: null,
                    observacion: null,
                },
                acciones: [{
                        id: 1,
                        descripcion: 'Entrada'
                    },
                    {
                        id: 2,
                        descripcion: 'Salida'
                    },
                ],
                quantity: 1,
                movimientosKardex: [],
                page: 1,
                per_page: 10,
                total: 0,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
                productoError: '',
            },
            methods: {
                //Crear
                sendForm() {


                    if(this.kardex.length == 0){
                        swal.fire({
                            title: 'Error',
                            text: 'No se ha seleccionado ningun producto',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                        });
                        return;
                    }

                    this.validateForm(this.kardex);

                    if (Object.keys(this.errors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitForm').innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButton').disabled = true;

                        axios({
                            method: 'post',
                            url: '/kardex/store',
                            data: this.kardex
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;



                            //Cerrar modal
                            document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Movimiento creado',
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
                                text: 'Ha ocurrido un error al crear el movimiento',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                'Guardar';

                            //limpiar
                            this.cleanForm();
                            //Recargar productos
                            this.getAllProductos();
                            this.getAllKardex();
                        })

                    } else {
                        swal.fire({
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                        });
                    }
                },
                //Eliminar
                deleteKardex(id) {
                    this.kardex = this.kardex.filter(kard => kard.id != id);
                },
                //Tabla
                getEntradas(id) {
                    let entradas = 0;
                    this.movimientosKardex.forEach(movimiento => {
                        if (movimiento.producto == id && movimiento.accion == 1) {
                            entradas += movimiento.cantidad;
                        }
                    });
                    return entradas;
                },
                getSalidas(id) {
                    let salidas = 0;
                    this.movimientosKardex.forEach(movimiento => {
                        if (movimiento.producto == id && movimiento.accion == 2) {
                            salidas += movimiento.cantidad;
                        }
                    });
                    return salidas;
                },
                getTotal(id) {
                    let total = this.mainProductos.find(producto => producto.id == id).stockInicial;

                    this.movimientosKardex.forEach(movimiento => {
                        if (movimiento.producto == id && movimiento.accion == 1) {
                            total += movimiento.cantidad;
                        } else if (movimiento.producto == id && movimiento.accion == 2) {
                            total -= movimiento.cantidad;
                        }
                    });

                    return total;
                },
                //Validaciones
                validateForm(kardex) {
                    this.errors = {};

                    for (let i = 0; i < kardex.length; i++) {

                        if (!kardex[i].cantidad || kardex[i].cantidad < 1) {
                            this.errors.cantidad = 'La cantidad es obligatoria';
                            document.getElementById('cantidad' + kardex[i].id).classList.add('is-invalid');
                        }

                    }

                },
                validateMinStock(kard) {

                    let id = kard.id;

                    this.errors = {};

                    let cantidad = document.getElementById('cantidad' + id).value;
                    let stock = this.kardex.find(kard => kard.id == id).item.stock;

                    if (kard.accion == 1) {
                        return;
                    }

                    if (cantidad > stock) {
                        document.getElementById('cantidad' + id).classList.add('is-invalid');
                        this.errors.cantidad = 'La cantidad no puede ser mayor al stock';
                    } else {
                        document.getElementById('cantidad' + id).classList.remove('is-valid');
                        this.errors.cantidad = '';
                    }
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
                //Busqueda
                searchFn() {

                    this.searchError = '';

                    //Si el campo empieza con un numero seguido de un * o x se considera el numero como la cantidad y se busca el producto
                    //Ejemplo: 5*producto o 5xproducto busca el producto y establece la cantidad en 5
                    if (this.search) {
                        let search = this.search.toLowerCase();
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
                    } else {
                        this.results = [];
                    }
                },
                cleanForm() {

                    this.kardex = [];
                    this.kardexItem = {
                        id: null,
                        item: null,
                        producto: null,
                        accion: null,
                        cantidad: null,
                        observacion: null,
                    };

                    this.errors = {};
                    this.searchError = '';
                    this.search = null;
                    this.results = [];
                    this.quantity = 1;

                    if (this.searchProductos.length == 0) {
                        this.productos = [];
                        this.searchProductos = [];
                        this.getAllProductos();
                    } else {
                        this.productos = this.searchProductos;
                    }

                    this.cleanSearchMain();
                },
                cleanSearch() {
                    this.search = null;
                    this.productos = this.searchProductos;
                    this.searchError = '';
                    this.results = [];
                },
                selectResult(result) {
                    this.kardexItem.id = result.id;
                    this.kardexItem.producto = result.nombre;
                    this.kardexItem.item = result;
                    this.kardexItem.accion = 1;
                    this.kardexItem.cantidad = this.quantity || 1;
                    this.kardexItem.observacion = '';
                    this.kardex.push(this.kardexItem);
                    this.results = [];
                    this.search = null;
                    this.quantity = 1;
                    this.kardexItem = {
                        id: null,
                        item: null,
                        producto: null,
                        accion: null,
                        cantidad: null,
                        observacion: null,
                    };
                },
                searchProductoFn() {
                    this.searchError = '';

                    if (!this.searchMainProductos) {
                        this.searchError = 'El campo de busqueda esta vacio';
                        return;
                    }

                    axios({
                        method: 'get',
                        url: '/allProductos',
                        params: {
                            search: this.searchMainProductos,
                            page: this.page,
                            per_page: this.per_page,
                            onlyActive: true,
                        }
                    }).then(response => {

                        this.mainProductos = response.data.data;

                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;
                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.searchProductoFn();
                        } else {
                            this.page = response.data.current_page;
                        }
                        this.per_page = response.data.per_page;
                        this.nextPageUrl = response.data.next_page_url;
                        this.prevPageUrl = response.data.prev_page_url;

                    }).catch(error => {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar el producto',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    })
                },
                cleanSearchMain() {
                    this.searchMainProductos = '';
                    this.searchError = '';
                    this.getAllProductos();
                },
                //Obtener recursos
                async getAllProductos() {

                    this.loading = true;
                    this.errors = {};

                    //Productos para lista de kardex
                    axios({
                        method: 'get',
                        url: '/allProductos',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            onlyActivePaginate: true,
                            search: this.searchMainProductos,
                        },
                    }).then(response => {

                        this.productoError = response.data.error;

                        if (this.productoError) {
                            this.loading = false;
                            this.searchProductos = [];
                            this.mainProductos = [];
                            this.searchMainProd = [];
                            return;
                        }

                        this.loading = false;
                        this.searchProductos = response.data.data;
                        this.mainProductos = response.data.data;
                        this.searchMainProd = response.data.data;
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
                            text: 'Ha ocurrido un error al obtener las unidades',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });

                    //Productos para select kardex
                    axios({
                        method: 'get',
                        url: '/allProductos',
                        params: {
                            //page: this.page,
                            //per_page: this.per_page,
                            onlyActive: true,
                            //search: this.searchMainProductos,
                        },
                    }).then(response => {

                        this.productoError = response.data.error;

                        if (this.productoError) {
                            this.productos = [];
                            return;
                        }

                        this.productos = response.data;

                    }).catch(error => {
                        this.loading = false;
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las unidades',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });

                },
                async getAllEstados() {

                    try {
                        let response = await fetch('/allEstados');
                        let data = await response.json();

                        this.estados = data;

                    } catch (error) {

                    }

                },
                async getAllProveedores() {
                    let response = await fetch('/allProveedores');
                    let data = await response.json();
                    this.proveedores = data;
                },
                async getAllKardex() {
                    let response = await fetch('/allKardex');
                    let data = await response.json();
                    this.movimientosKardex = data;
                },
            },
            mounted() {
                this.getAllKardex();
                this.getAllProveedores();
                this.getAllEstados();
                this.getAllProductos();
            }
        });
    </script>
@endsection
