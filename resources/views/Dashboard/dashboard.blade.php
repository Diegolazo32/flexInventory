@extends('layouts.Navigation')

@section('title', 'Dashboard')

@section('content')
    <div id="app">
        <div class="row">
            <!-- Productos -->
            <div class="col-md-3" style="margin-bottom: 15px;">
                <div class="card customShadow blue1 hoverCardLeft"
                    style="height: auto; display: flex; justify-content: center;">
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
                    style="height: auto; display: flex; justify-content: center;">
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
                    style="height: auto; display: flex; justify-content: center;">
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
                    style="height: auto; display: flex; justify-content: center;">
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

        <div class="row">
            <div class="col-lg-4" style="margin-bottom: 15px;">
                <!-- Ultimos productos vendidos -->
                <div class="card customShadow">
                    <div class="card-body" style="text-align: center">

                        <h5>Productos proximos a vencer</h5>

                        <div class="table-responsive">

                            <div v-if="loading" class="spinner-border text-primary" role="status">
                                <span class="sr-only"></span>

                            </div>

                            <div v-if="!loading && productosError" class="alert alert-danger" role="alert">
                                @{{ productosError }}
                            </div>

                            <div v-if="!productosError && !loading && productosVencimiento.length == 0" class="alert alert-warning" role="alert">
                                No hay productos proximos a vencer
                            </div>

                            <table v-if="!loading && productosVencimiento.length > 0" ref="table"
                                class="table table-striped table-hover" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Fecha de vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in productosVencimiento">
                                        <td>@{{ producto.nombre }}</td>
                                        <td>@{{ formatDate(producto.fechaVencimiento) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-4" style="margin-bottom: 15px;">

                <div class="card customShadow">
                    <div class="card-body" style="text-align: center">
                        <h1>Bienvenido {{ Auth::user()->nombre }}</h1>
                    </div>
                </div>

            </div>

            <div class="col-lg-4" style="margin-bottom: 15px;">

                <div class="card customShadow">
                    <div class="card-body" style="text-align: center">
                        <h1>Bienvenido {{ Auth::user()->nombre }}</h1>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                productos: [],
                productosVencimiento: [],
                loading: true,
                productosError: ''
            },
            methods: {
                //Parse
                formatDate(date) {

                    if (!date) {
                        return;
                    }

                    let fecha = new Date(date);
                    let options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return fecha.toLocaleDateString('es-ES', options);
                },
                //Obtener recursos
                async getAllProductos() {
                    let response = await fetch('/allProductos');
                    let data = await response.json();
                    this.loading = false;

                    if (data.error) {
                        this.productosError = data.error;
                        return;
                    }

                    this.productos = data;

                    this.productos.forEach(producto => {

                        if (producto.fechaVencimiento != null) {

                            let fechaVencimiento = new Date(producto.fechaVencimiento);
                            let fechaActual = new Date();

                            let diffTime = Math.abs(fechaVencimiento - fechaActual);
                            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                            if (diffDays <= 7) {
                                this.productosVencimiento.push(producto);
                            }

                        }

                    });
                },

            },
            mounted() {
                this.getAllProductos();
            }
        });
    </script>
@endsection
