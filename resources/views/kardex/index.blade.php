@extends('layouts.Navigation')

@section('title', 'Kardex')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Kardex</h1>
                        <small class="text-muted">@{{ mensaje }}</small>
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
                                <input :disabled="mainProductos < 1" type="text" class="form-control"
                                    placeholder="Buscar producto" v-model="searchMainProductos" @keyup="searchProductoFn">
                                <small class="text-danger" v-if="searchError">@{{ searchError }}</small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de productos -->
            <div class="row">
                <div class="card-body">


                    <div v-if="productos.error" class="alert alert-danger" role="alert">
                        <h3>@{{ productos.error }}</h3>
                    </div>

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
                                    <td colspan="6"><i class="fas fa-spinner fa-spin"></i> Cargando productos...</td>
                                </tr>
                                <!-- vue foreach -->
                                <tr v-else v-for="producto in productos" :key="producto.id">
                                    <td>@{{ producto.codigo }}</td>
                                    <td>@{{ producto.nombre }}</td>
                                    <td>@{{ producto.stockInicial }}</td>
                                    <td style="color:green; font-weight:bold;">@{{ getEntradas(producto.id) }}</td>
                                    <td style="color: red; font-weight:bold;">@{{ getSalidas(producto.id) }}</td>
                                    <td style="font-weight:bold;">@{{ getTotal(producto.id) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        <div class="d-flex justify-content-center" style="gap: 10px;">
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item" :disabled="page === 1">
                                                    <a class="page-link" href="#" aria-label="Previous"
                                                        @click="pageMinus">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li class="page-item" v-for="pageNumber in totalPages"
                                                    :key="pageNumber" :class="{ active: pageNumber === page }">
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down mdl">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearProductoModalLabel">Realizar movimiento </h1>
                        <small class="text-muted">Seleccione los productos para realizar un movimiento</small>
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
                                                @keyup.enter="selectResult(result)" tabindex="0">
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
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Accion</th>
                                                    <th scope="col">Cantidad</th>
                                                    <th scope="col">Observacion</th>
                                                    <th scope="col">Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading" role="alert" id="loading">
                                                    <td colspan="5"><i class="fas fa-spinner fa-spin"></i> Cargando
                                                        productos...</td>
                                                </tr>
                                                <!-- vue foreach -->
                                                <tr v-else v-for="kard in kardex" :key="kard.id">
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
                                                            min="0" maxlength="6">
                                                        <small :id="'errorCantidad' + kard.id" class="text-danger"
                                                            v-if="errors.cantidad"></small>
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mdl">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="infoProductoModalLabel">¿Como funciona el Kardex?</h1>
                    </div>
                    <div class="modal-body">
                        <p>El Kardex es un sistema de control de inventarios que permite llevar un registro de las
                            entradas y salidas de productos en un almacén. El Kardex se puede llevar de forma manual o
                            automatizada, y es una herramienta fundamental para la gestión de inventarios.</p>
                        <p> Primero seleccione un producto y agreguelo al kardex, a continuacion establezca la accion
                            que desea realizar, ya sea una entrada o una salida de producto, luego ingrese la cantidad y
                            finalmente presione el boton de guardar.</p>
                        <p>El sistema se encargara de realizar la operacion correspondiente y actualizar el stock del
                            producto.</p>
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
                search: null,
                searchMainProductos: null,
                mainProductos: [],
                searchMainProd: [],
                errors: {},
                productos: [],
                searchProductos: [],
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
                per_page: 5,
                total: 0,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
            },
            methods: {
                //Crear
                sendForm() {
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

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                '<i class="fas fa-save"></i>';

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

                            //limpiar
                            this.cleanForm();
                            //Recargar productos
                            this.getAllProductos();
                        })

                    }
                },
                //Eliminar
                deleteKardex(id) {
                    this.kardex = this.kardex.filter(kard => kard.id != id);
                    console.log(this.kardex);
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
                    let total = 0;
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
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllUnidades();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllUnidades();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllUnidades();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllUnidades();
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

                            console.log(this.quantity);

                            //Nombre sera el resto de la cadena despues del * o x
                            let nombre = search.split('*')[1] || search.split('x')[1];

                            console.log(nombre);

                            //Buscar producto
                            this.filtered = productos.filter(producto => {
                                return producto.nombre.toLowerCase().includes(search)
                            });

                            try {
                                this.filtered = productos.filter(producto => {
                                    return producto.nombre.toLowerCase().includes(nombre)
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
                                    return producto.nombre.toLowerCase().includes(search)
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
                },
                cleanSearch() {
                    this.search = null;
                    this.productos = this.searchProductos;
                    this.searchError = '';
                    this.results = [];
                },
                selectResult(result) {

                    console.log(result);

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

                    console.log(this.kardex);
                },
                searchProductoFn() {

                    this.searchError = '';

                    if (this.searchMainProductos) {
                        let search = this.searchMainProductos.toLowerCase();
                        let productos = this.searchMainProd;

                        try {
                            this.filtered = productos.filter(producto => {
                                return producto.nombre.toLowerCase().includes(search)
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
                            this.productos = ['No se encontraron resultados'];
                        }

                        if (this.filtered.length > 5) {
                            this.productos = this.filtered.slice(0, 5);
                        } else {
                            this.productos = this.filtered;
                        }
                    } else {
                        this.productos = this.searchMainProd;
                    }



                },
                //Obtener recursos
                async getAllProductos() {
                    let response = await fetch('/allProductos');
                    let data = await response.json();
                    this.loading = false;
                    this.productos = data;
                    this.searchProductos = data;
                    this.mainProductos = data;
                    this.searchMainProd = data;
                },
                async getAllEstados() {

                    try {
                        let response = await fetch('/allEstados');
                        let data = await response.json();

                        this.estados = data;

                        //console.log(this.estados);

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
                async getAllKardex() {
                    let response = await fetch('/allKardex');
                    let data = await response.json();
                    this.movimientosKardex = data;
                },

            },
            mounted() {
                this.getAllKardex();
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
