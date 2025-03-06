@extends('Layouts.Navigation')

@section('title', 'Dashboard')

@section('content')
    <div id="app">
        <div class="row" style="display: flex; justify-content: center; gap: 10px;">
            @if ($Activo == false)
                <div class="card mb-1 col-lg-5 hoverCard">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="rounded-3">
                                    <div class="container-fluid">
                                        <h1 class="display-6 fw-bold">Abrir nuevo inventario</h1>
                                        <p class="col-lg-12 fs-4"> Debe de tener un inventario activo para poder
                                            realizar operaciones con los productos.</p>
                                        <p class="col-lg-12 fs-6">Si no tiene un inventario activo, no podrá crear
                                            productos,
                                            realizar compras, hacer movimientos de kardex, o generar reportes.
                                        </p>
                                        <button class="btn btn-success btn-lg" id="openBtn" type="button"
                                            onClick="window.location.href='{{ route('inventario') }}'">Abrir
                                            nuevo
                                            inventario</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($empresa->firstTime == true)
                <div class="card mb-1 col-lg-5 hoverCard">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="rounded-3">
                                    <div class="container-fluid">
                                        <h1 class="display-6 fw-bold">Actualice la información de su negocion</h1>
                                        <p class="col-lg-12 fs-4">
                                            Ingrese la informacion de su empresa para poder generar reportes y
                                            facturas.
                                        </p>
                                        <p class="col-lg-12 fs-6">Sin la informacion de su empresa los documentos podrian
                                            no
                                            ser validos.
                                        </p>
                                        <button class="btn btn-warning btn-lg" id="openBtn" type="button"
                                            onClick="window.location.href='{{ route('empresa') }}'">Actualizar
                                            informacion</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($productos->count() == 0)
                <div class="card mb-1 col-lg-5 hoverCard">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="rounded-3">
                                    <div class="container-fluid">
                                        <h1 class="display-6 fw-bold">No hay productos registrados</h1>
                                        <p class="col-lg-12 fs-4">Debe de registrar productos para poder realizar
                                            operaciones con ellos.</p>
                                        <p class="col-lg-12 fs-6">Si no tiene productos registrados, no podrá realizar
                                            compras, hacer movimientos de kardex, o generar reportes.
                                        </p>
                                        <button class="btn btn-primary btn-lg" id="openBtn" type="button"
                                            onClick="window.location.href='{{ route('productos') }}'">Registrar
                                            productos</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($resolucionActiva == null)
                <div class="card mb-1 col-lg-5 hoverCard">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="rounded-3">
                                    <div class="container-fluid">
                                        <h1 class="display-6 fw-bold">No hay resolucion de tickets activa</h1>
                                        <p class="col-lg-12 fs-4">Debe de registrar una resolucion de tickets para poder
                                            realizar ventas.</p>
                                        <p class="col-lg-12 fs-6">Si no tiene una resolucion de tickets activa, no podrá
                                            realizar ventas, ni generar facturas.
                                        </p>
                                        <button class="btn btn-lg" id="openBtn" type="button"
                                            style="background-color: #fd7e14; color: white;"
                                            onClick="window.location.href='{{ route('tickets') }}'">Registrar
                                            resolucion</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <!-- Productos -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow hoverCardLeft"
                    style="height: auto; display: flex; justify-content: center; background-color: #007bff; color: white;">
                    <button type="button" class="btn" onclick="window.location.href='{{ route('productos') }}'"
                        style="color: white;">
                        <div class="card-body row" style="text-align: center">
                            <div class="col-lg-12 "
                                style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <i class="fas fa-box" style="font-size: 40px;"></i>
                                <h5>Productos</h6>
                            </div>
                            <div class="col-lg-12 " style="display: flex; align-items: center; justify-content: center;">
                                <h1> {{ $productos->count() }} </h1>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Categorias -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow green1 hoverCardCenter"
                    style="height: auto; display: flex; justify-content: center; background-color: #0d866c; color: white;">
                    <button type="button" class="btn" onclick="window.location.href='{{ route('categorias') }}'"
                        style="color: white;">
                        <div class="card-body row" style="text-align: center">
                            <div class="col-lg-12 "
                                style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <i class="fas fa-list" style="font-size: 40px; color: white;"></i>
                                <h5>Categorias</h6>
                            </div>
                            <div class="col-lg-12 " style="display: flex; align-items: center; justify-content: center;">
                                <h1> {{ $categorias->count() }} </h1>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Clientes -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow orange2 hoverCardCenter"
                    style="height: auto; display: flex; justify-content: center; background-color: #ffb300; color: white;">
                    <button type="button" class="btn" onclick="window.location.href='{{ route('clientes') }}'"
                        style="color: white;">
                        <div class="card-body row" style="text-align: center">
                            <div class="col-lg-12 "
                                style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <i class="fas fa-users" style="font-size: 40px; color: white;"></i>
                                <h5>Clientes</h6>
                            </div>
                            <div class="col-lg-12 " style="display: flex; align-items: center; justify-content: center;">
                                <h1> {{ $clientes->count() }} </h1>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Proveedores -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow purple2 hoverCardRight"
                    style="height: auto; display: flex; justify-content: center;     background-color: #3d0072; color: white;">
                    <button type="button" class="btn" onclick="window.location.href='{{ route('proveedores') }}'"
                        style="color: white;">
                        <div class="card-body row" style="text-align: center">
                            <div class="col-lg-12 "
                                style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <i class="fas fa-truck" style="font-size: 40px; color: white;"></i>
                                <h5>Proveedores</h6>
                            </div>
                            <div class="col-lg-12 " style="display: flex; align-items: center; justify-content: center;">
                                <h1> {{ $proveedores->count() }} </h1>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        <br>
        <!--Tablas de informacion de productos-->
        <div class="row">
            <div class="col-lg-4" style="margin-bottom: 15px;">
                <!-- Productos proximos a vencer -->
                <div class="card hoverCardCenter customShadow">
                    <div class="card-body" style="text-align: center">

                        <h5>Lotes proximos a vencer</h5>

                        <div class="table-responsive">

                            <div v-if="loading" class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>

                            <div v-if="!loading && lotesError" class="alert alert-danger" role="alert">
                                @{{ lotesError }}
                            </div>

                            <div v-if="!lotesError && !loading && lotesVencimiento.length == 0"
                                class="alert alert-warning" role="alert">
                                No hay productos proximos a vencer
                            </div>

                            <table v-if="!loading && lotesVencimiento.length > 0" ref="table"
                                class="table table-striped table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <!--<th scope="col">Codigo</th>-->
                                        <th scope="col">Producto</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">N°</th>
                                        <th scope="col">Fecha de vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in lotesVencimiento">
                                        <!--<td>@{{ lote.codigo }}</td>-->
                                        <td>@{{ lote.producto }}</td>
                                        <td>@{{ lote.cantidad }}</td>
                                        <td>@{{ lote.numero }}</td>
                                        <td>@{{ formatDate(lote.fechaVencimiento) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-4" style="margin-bottom: 15px;">

                <div class="card hoverCardCenter customShadow">
                    <div class="card-body" style="text-align: center">

                        <h5>Productos proximos a agotarse</h5>

                        <div class="table-responsive">

                            <div v-if="loading" class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>

                            <div v-if="!loading && agotadosError" class="alert alert-danger" role="alert">
                                @{{ agotadosError }}
                            </div>

                            <div v-if="!agotadosError && !loading && productosAgotados.length == 0"
                                class="alert alert-warning" role="alert">
                                No hay productos proximos a agotarse
                            </div>

                            <table v-if="!loading && productosAgotados.length > 0" ref="table"
                                class="table table-striped table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Mínimo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in productosAgotados">
                                        <td>@{{ producto.nombre }}</td>
                                        <td>@{{ producto.stock }}</td>
                                        <td>@{{ producto.stockMinimo ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-4" style="margin-bottom: 15px;">

                <div class="card hoverCardCenter customShadow">
                    <div class="card-body" style="text-align: center">

                        <h5>Productos con stock excedido</h5>

                        <div class="table-responsive">

                            <div v-if="loading" class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>
                            </div>

                            <div v-if="!loading && excedidosError" class="alert alert-danger" role="alert">
                                @{{ excedidosError }}
                            </div>

                            <div v-if="!excedidosError && !loading && productosExcedidos.length == 0"
                                class="alert alert-warning" role="alert">
                                No hay productos excedido
                            </div>

                            <table v-if="!loading && productosExcedidos.length > 0" ref="table"
                                class="table table-striped table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Stock actual</th>
                                        <th scope="col">Stock maximo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in productosExcedidos">
                                        <td>@{{ producto.nombre }}</td>
                                        <td>@{{ producto.stock }}</td>
                                        <td>@{{ producto.stockMaximo ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <br>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                lotesVencimiento: [],
                productosAgotados: [],
                productosExcedidos: [],
                loading: true,
                lotesError: null,
                agotadosError: null,
                excedidosError: null,
            },
            methods: {
                //Parse
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

                //Obtener lotes proximos a vencer
                async getLotesVencimiento() {
                    axios({
                        method: 'get',
                        url: '/getLotesVencimiento',
                    }).then(response => {

                        if (response.data.error) {
                            this.lotesError = response.data.error;
                            return;
                        }

                        this.lotesVencimiento = response.data;
                    }).catch(error => {
                        this.lotesError = 'Error al obtener los lotes proximos a vencer';
                    });
                },
                async getProductosStockMinimo() {
                    axios({
                        method: 'get',
                        url: '/getProductosStockMinimo',
                    }).then(response => {

                        if (response.data.error) {
                            this.agotadosError = response.data.error;
                            return;
                        }

                        this.productosAgotados = response.data;
                    }).catch(error => {
                        this.agotadosError = 'Error al obtener los productos con stock minimo';
                    });
                },
                async getProductosOverstock() {
                    axios({
                        method: 'get',
                        url: '/getProductosOverStock',
                    }).then(response => {

                        if (response.data.error) {
                            this.excedidosError = response.data.error;
                            this.loading = false;
                            return;
                        }

                        this.productosExcedidos = response.data;
                        this.loading = false;
                    }).catch(error => {
                        this.excedidosError = 'Error al obtener los productos con stock excedido';
                        this.loading = false;
                    });
                },

            },
            mounted() {
                this.getLotesVencimiento();
                this.getProductosStockMinimo();
                this.getProductosOverstock();
            }
        });
    </script>
@endsection
