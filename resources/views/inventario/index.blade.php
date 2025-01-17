@extends('layouts.Navigation')

@section('title', 'Inventario')

@section('content')
    <div id="app">

        <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
            <i class="fas fa-spinner fa-spin"></i> Cargando...
        </div>

        <div class="row">
            <div class="col-lg-1"></div>

            <!--Inventario activo-->
            <div v-if="!loading && inventarioActivo" class="card mb-3 col-lg-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="rounded-3">
                                <div class="container-fluid">
                                    <h1 class="display-6 fw-bold">Inventario Activo n°@{{ inventarioActivo.id }}</h1>
                                    <hr>
                                    <p class="col-lg-12 fs-4"> Fecha de apertura: @{{ inventarioActivo.fechaApertura }}</p>
                                    <p class="col-lg-12 fs-4"> Aperturado por: @{{ getName(inventarioActivo.aperturadoPor) }}</p>
                                    <p class="col-lg-12 fs-4"> Estado:
                                        <span class="badge bg-success">@{{ getEstado(inventarioActivo.estado) }}</span>
                                    </p>
                                    <hr>
                                    <p class="col-lg-12 fs-4"> Productos en apertura: @{{ inventarioActivo.ProductosApertura }}</p>
                                    <p class="col-lg-12 fs-4"> Stock en apertura: @{{ inventarioActivo.StockApertura }}</p>
                                    <p class="col-lg-12 fs-4"> Valor en apertura: $@{{ inventarioActivo.totalInventario }}</p>
                                    <hr>
                                    <p class="col-lg-12 fs-4"> Valor actual: $@{{ totalValueInventory }}</p>
                                    <p class="col-lg-12 fs-4"> Stock actual: @{{ totalQuantityInventory }}</p>
                                    <p class="col-lg-12 fs-4"> Productos actuales: @{{ totalUniqueProducts }}</p>

                                    <button class="btn btn-danger btn-lg" id="closeBtn" @click="closeInventory"
                                        type="button">Cerrar inventario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-1"></div>

            <!--Inventario cerrado-->
            <div v-if="!loading && inventarioCerrado" class="card mb-3 col-lg-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="rounded-3">
                                <div class="container-fluid">
                                    <h1 class="display-6 fw-bold">Inventario Cerrado n°@{{ inventarioCerrado.id }}</h1>
                                    <hr>
                                    <p class="col-lg-12 fs-4"> Fecha de cierre: @{{ inventarioCerrado.fechaCierre }}</p>
                                    <p class="col-lg-12 fs-4"> Cerrado por: @{{ getName(inventarioCerrado.cerradoPor) }}</p>
                                    <p class="col-lg-12 fs-4"> Estado:
                                        <span class="badge bg-danger">@{{ getEstado(inventarioCerrado.estado) }}</span>
                                    </p>
                                    <hr>
                                    <p class="col-lg-12 fs-4"> Productos en cierre: @{{ inventarioCerrado.ProductosCierre }}</p>
                                    <p class="col-lg-12 fs-4"> Stock en cierre: @{{ inventarioCerrado.StockCierre }}</p>
                                    <p class="col-lg-12 fs-4"> Valor en cierre: $@{{ inventarioCerrado.totalInventario }}</p>
                                    <!--<p class="col-lg-12 fs-4"> Stock total: @{{ totalQuantityInventory }}</p>-->
                                    <hr>


                                    <button v-if="!inventarioActivo" class="btn btn-success btn-lg" id="openBtn"
                                        type="button" @click="openInventory">Abrir nuevo
                                        inventario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" v-if="!loading && !inventarioActivo && !inventarioCerrado">
            <div class="card mb-3 col-lg-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="rounded-3">
                                <div class="container-fluid">
                                    <h1 class="display-6 fw-bold">Abrir nuevo inventario</h1>
                                    <p class="col-lg-12 fs-4"> Debe de tener un inventario activo para poder realizar
                                        operaciones con los productos </p>
                                    <button class="btn btn-success btn-lg" id="openBtn" type="button"
                                        @click="openInventory">Abrir nuevo
                                        inventario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                inventarioActivo: {},
                inventarioCerrado: {},
                loading: true,
                estados: [],
                users: [],
                productos: [],
                totalValueInventory: 0,
                totalQuantityInventory: 0,
                totalUniqueProducts: 0,
            },
            methods: {
                //Crear
                openInventory() {
                    this.loading = true;
                    document.getElementById('openBtn').disabled = true;
                    axios({
                        method: 'post',
                        url: '/inventario/open',
                    }).then(response => {
                        if (response.data.success) {
                            swal.fire({
                                title: 'Inventario abierto',
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
                            text: 'Ha ocurrido un error',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {
                        this.inventarioActivo = {};
                        this.inventarioCerrado = {};
                        this.getAllUsers();
                        this.getAllEstados();
                        this.getAllProductos();
                        this.getAllInventarios();
                    });
                },
                closeInventory() {
                    this.loading = true;
                    document.getElementById('closeBtn').disabled = true;
                    axios({
                        method: 'post',
                        url: '/inventario/close',
                        data: {
                            id: this.inventarioActivo.id
                        }
                    }).then(response => {

                        if (response.data.success) {
                            swal.fire({
                                title: 'Inventario cerrado',
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
                            text: 'Ha ocurrido un error',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });


                    }).finally(() => {
                        this.inventarioActivo = {};
                        this.inventarioCerrado = {};

                        this.getAllUsers();
                        this.getAllEstados();
                        this.getAllProductos();
                        this.getAllInventarios();
                    });
                },
                //Recursos
                async getAllEstados() {
                    axios({
                        method: 'get',
                        url: '/allEstados'
                    }).then(response => {
                        this.estados = response.data;
                    }).catch(error => {

                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'

                        });

                    });
                },
                async getAllProductos() {

                    this.totalValueInventory = 0;
                    this.totalUniqueProducts = 0;
                    this.totalQuantityInventory = 0;

                    axios({
                        method: 'get',
                        url: '/allProductos',
                        data: {
                            inventory: true
                        }
                    }).then(response => {

                        if (response.data.error) {
                            return;
                        }
                        this.productos = response.data;

                        this.productos.forEach(producto => {
                            this.totalValueInventory += (producto.precioCompra * producto
                                .stock);
                            this.totalQuantityInventory += producto.stock;
                        });

                        this.totalUniqueProducts = this.productos.length;


                    }).catch(error => {

                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los productos',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    });



                },
                async getAllUsers() {
                    axios({
                        method: 'get',
                        url: '/allUsers'
                    }).then(response => {
                        this.users = response.data;
                    }).catch(error => {

                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    });
                },
                async getAllInventarios() {
                    this.inventarioActivo = {};
                    this.inventarioCerrado = {};
                    axios({
                        method: 'get',
                        url: '/allInventario'
                    }).then(response => {
                        this.inventarioActivo = response.data.inventarioActivo;
                        this.inventarioCerrado = response.data.inventarioCerrado;
                    }).catch(error => {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }).finally(() => {
                        this.loading = false;
                    });
                },
                getName(id) {
                    let user = this.users.find(user => user.id == id);
                    return user.nombre + ' ' + user.apellido;
                },
                getEstado(id) {
                    let estado = this.estados.find(estado => estado.id == id);
                    return estado.descripcion;
                }
            },
            mounted() {
                this.getAllUsers();
                this.getAllEstados();
                this.getAllProductos();
                this.getAllInventarios();
            }
        });
    </script>
@endsection
