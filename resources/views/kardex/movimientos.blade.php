@extends('Layouts.Navigation')

@section('title', 'Movimientos')

@section('content')
    <div id="app">
        <div class="card hoverCard" >
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Movimientos</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                                        data-bs-target="#crearMovimientoModal" style="height: 40px;">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>

                                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editMovimientoModalBtn"
                                                                        data-bs-target="#editMovimientoModal" style="height: 40px;" hidden>
                                                                        Editar movimiento
                                                                    </button>

                                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteMovimientoModalBtn"
                                                                        data-bs-target="#deleteMovimientoModal" style="height: 40px;" hidden>
                                                                        Eliminar movimiento
                                                                    </button>-->
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
                                    placeholder="Buscar por nombre, descripcion o grupo" v-model="search">
                                <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllKardex"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click=""><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabla de movimientos -->
            <div class="row">
                <div class="card-body">
                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="movimientos.error" class="alert alert-danger" role="alert">
                        <h3>@{{ movimientos.error }}</h3>
                    </div>

                    <div v-if="movimientos.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Correlativo</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Accion</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col">Inventario</th>
                                    <th scope="col">Fecha</th>

                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='movimiento in movimientos' :key="movimiento.id">
                                    <td>
                                        @{{ movimiento.id }}
                                    </td>
                                    <td>
                                        @{{ getProductName(movimiento.producto) }}
                                    </td>
                                    <td v-if="movimiento.accion == 1">
                                        <span class="badge bg-success">Entrada</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger">Salida</span>
                                    </td>
                                    <td>
                                        @{{ movimiento.cantidad }}
                                    </td>
                                    <td>
                                        @{{ movimiento.observacion }}
                                    </td>
                                    <td>
                                        @{{ movimiento.inventario }}
                                    </td>
                                    <td>
                                        @{{ parseDate(movimiento.created_at) }}
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

    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                loading: true,
                movimientos: [],
                productos: [],
                search: '',
                searchError: '',
                page: 1,
                per_page: 10,
                total: 0,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
            },
            methods: {
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllKardex();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllKardex();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllKardex();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllKardex();
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
                getProductName(id) {
                    let producto = this.productos.find(producto => producto.id == id);
                    return producto.nombre;

                },

                async getAllKardex() {
                    this.loading = true;
                    axios({
                        method: 'get',
                        url: '/allKardex',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            search: this.search
                        }
                    }).then(response => {

                        this.movimientos = response.data.data;


                        //Paginacion
                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;

                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.getAllKardex();
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
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los movimientos',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {
                        this.loading = false;
                    })
                },

                async getAllProductos() {
                    axios({
                        method: 'get',
                        url: '/allProductos',
                    }).then(response => {
                        this.productos = response.data;
                    }).catch(error => {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los productos',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                },
            },
            mounted() {
                this.getAllProductos();
                this.getAllKardex();
            }
        });
    </script>
@endsection
