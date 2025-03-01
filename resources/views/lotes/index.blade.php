@extends('Layouts.Navigation')

@section('title', 'Lotes')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Lotes</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end" style="display: flex; gap: 5px;">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#infoLoteModal"
                            style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-info"></i>
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
                                <input type="text" class="form-control" name="search" @keyup.enter="getAllLotes"
                                    placeholder="Buscar por codigo o nombre" v-model="searchLotes">
                                <!--<small class="text-danger" v-if="searchError">@{{ searchError }}</small>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabla de lotes -->
            <div class="row">
                <div class="card-body">

                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>


                    <div v-if="loteError" class="alert alert-danger" role="alert">
                        <h3>@{{ loteError }}</h3>
                    </div>

                    <div v-if="lotes.length == 0 && !loading && !loteError" class="alert alert-warning" role="alert">
                        <h3>No hay lotes registrados</h3>
                    </div>

                    <div v-if="lotes.length > 0 && !loading" class="table-responsive">
                        <table ref="table" class="table table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">N° de lote</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Fecha de Vencimiento</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Inventario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="loading" role="alert" id="loading">
                                    <td colspan="8"><i class="fas fa-spinner fa-spin"></i> Cargando lotes...</td>
                                </tr>
                                <!-- vue foreach -->
                                <tr v-else v-for="lote in lotes" :key="lote.id">
                                    <td>@{{ lote.codigo }}</td>
                                    <td>@{{ lote.numero }}</td>
                                    <td>@{{ getProductName(lote.producto) }}</td>
                                    <td style="font-weight: bold">@{{ lote.cantidad }}</td>
                                    <td>@{{ parseDate(lote.fechaVencimiento) ?? '-' }}</td>
                                    <td v-if="lote.estado == 1">
                                        <span class="badge bg-success">@{{ getEstado(lote.estado) }}</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger">@{{ getEstado(lote.estado) }}</span>
                                    </td>
                                    <td> @{{ lote.inventario }}</td>

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

        <!--Info modal-->
        <div class="modal fade" id="infoLoteModal" tabindex="-1" aria-labelledby="infoLoteModalLabel" aria-hidden="inert"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="infoLoteModalLabel">¿Como funcionan los lotes?</h1>
                    </div>
                    <div class="modal-body">
                        <p> Los lotes son la forma en como se controla la cantidad de productos que se tienen en
                            inventario, estos se identifican por un código único y un número de lote, el cual se asigna
                            automáticamente al momento de registrar un nuevo lote. </p>

                        <p> Estos ademas se diferencian por tener una fecha de vencimiento diferente por cada lote, para un
                            mismo producto,
                            lo que permite tener un control más preciso de las fechas de vencimiento que puede tener un
                            mismo producto.</p>

                        <p> Al realizar una compra de producto, se crea un nuevo lote con la cantidad de producto
                            comprado, y se asigna una fecha de vencimiento, la cual se puede modificar en cualquier
                            momento. </p>

                        <p> Al realizar una venta de producto, automáticamente se descuenta la cantidad de producto
                            vendido del lote más antiguo, y se crea un nuevo movimiento en el kardex. </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="cancelShowButton">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                loteError: '',
                loading: true,
                page: 1,
                per_page: 10,
                totalPages: 0,
                total: 0,
                searchLotes: '',
                lotes: [],
                productos: [],
            },
            methods: {
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllLotes();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllLotes();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllLotes();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllLotes();
                },
                //Busqueda
                searchFn() {

                },
                //Obtener recursos
                async getAllProductos() {
                    let response = await fetch('/allProductos');
                    let data = await response.json();
                    this.productos = data;
                },
                async getAllLotes() {
                    axios({
                        method: 'get',
                        url: '/allLotes',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            onlyActive: true,
                            search: this.searchLotes,
                        },
                    }).then(response => {

                        this.loteError = response.data.error;

                        if (this.loteError) {
                            this.loading = false;
                            //this.lotes = [];
                            this.searchLotes = [];
                            this.lotes = [];
                            return;
                        }

                        this.loading = false;
                        //this.searchLotes = response.data;
                        this.lotes = response.data.data;

                        //Paginacion
                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;
                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.getAllLotes();
                        } else {
                            this.page = response.data.current_page;
                        }
                        this.per_page = response.data.per_page;
                        this.nextPageUrl = response.data.next_page_url;
                        this.prevPageUrl = response.data.prev_page_url;


                    }).catch(error => {
                        this.loading = false;
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los lotes',
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
                    } catch (error) {}
                },


                getEstado(id) {
                    let estado = this.estados.find(estado => estado.id == id);
                    return estado.descripcion;
                },
                getProductName(id) {
                    let producto = this.productos.find(producto => producto.id == id);
                    return producto.nombre;
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
            },
            mounted() {
                this.getAllEstados();
                this.getAllProductos();
                this.getAllLotes();
            }
        });
    </script>
@endsection
