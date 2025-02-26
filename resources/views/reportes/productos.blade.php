@extends('Layouts.Navigation')

@section('title', 'Reporte de productos')

@section('content')
    <div id="app">

        <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-if="!loading">
            <div class="card">

                <div class="card-header">
                    <h1>Reporte de productos</h1>
                </div>


                <div class="card-body">
                    <form action="{{ route('reportes.productos.generar') }}" method="POST" id="formReporte">
                        @csrf
                        <div class="row" style="display: flex; justify-content: space-between;">
                            <div class="col-lg-3">
                                <!--Estado-->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="estado" name="estado" v-model="reportParams.estado"
                                        @blur="validateParams" @change="validateParams">
                                        <option value="0">Todos</option>

                                        <option v-for="estado in estados" :key="estado.id" :value="estado.id">
                                            @{{ estado.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Estado</label>
                                    <div class="invalid-tooltip" v-if="errors.estado">
                                        @{{ errors.estado }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- Categoria -->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="categoria" name="categoria"
                                        v-model="reportParams.categoria" @blur="validateParams" @change="validateParams">
                                        <option value="0">Todos</option>
                                        <option v-for="categoria in categorias" :key="categoria.id"
                                            :value="categoria.id">
                                            @{{ categoria.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Categoria</label>
                                    <div class="invalid-tooltip" v-if="errors.categoria">
                                        @{{ errors.categoria }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- Unidad -->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="unidad" name="unidad" v-model="reportParams.unidad"
                                        @blur="validateParams" @change="validateParams">
                                        <option value="0">Todos</option>

                                        <option v-for="unidad in unidades" :key="unidad.id" :value="unidad.id">
                                            @{{ unidad.descripcion }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Unidad</label>
                                    <div class="invalid-tooltip" v-if="errors.unidad">
                                        @{{ errors.unidad }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- Proveedor -->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="proveedor" name="proveedor"
                                        v-model="reportParams.proveedor" @blur="validateParams" @change="validateParams">
                                        <option value="0">Todos</option>

                                        <option v-for="proveedor in proveedores" :key="proveedor.id"
                                            :value="proveedor.id">
                                            @{{ proveedor.nombre }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Proveedor</label>
                                    <div class="invalid-tooltip" v-if="errors.proveedor">
                                        @{{ errors.proveedor }}
                                    </div>
                                </div>
                            </div>

                            <!--Generar pdf-->
                            <div class="col-lg-12" style="display: flex; align-items: center; justify-content: center;">
                                <button type="submit" class="btn btn-primary" @click="sendForm">
                                    <i class="fas fa-file-pdf"></i>
                                    Generar reporte</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {

                loading: true,

                //Parametros de reporte
                reportParams: {
                    estado: 0,
                    categoria: 0,
                    unidad: 0,
                    proveedor: 0,
                },

                //Errores
                errors: {},

                //estados
                estados: [],

                //Categorias
                categorias: [],

                //Unidades
                unidades: [],

                //Proveedores
                proveedores: [],

            },
            methods: {

                validateParams() {

                    this.errors = {};

                    document.getElementById('estado').setAttribute('class', 'form-control is-valid');
                    document.getElementById('categoria').setAttribute('class', 'form-control is-valid');
                    document.getElementById('unidad').setAttribute('class', 'form-control is-valid');
                    document.getElementById('proveedor').setAttribute('class', 'form-control is-valid');

                },

                //Enviar formulario
                sendForm() {

                    this.validateParams();

                    //sendForm
                    if (Object.keys(this.errors).length === 0) {
                        document.getElementById('formReporte').submit();
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Reporte',
                            text: 'Generando reporte',
                            icon: 'info',
                        });
                    } else {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                        });
                    }

                },

                //Recursos
                async getAllEstados() {

                    try {
                        axios({
                            method: 'get',
                            url: '/allEstados',
                            params: {
                                //page: this.page,
                                //per_page: this.per_page,
                                //search: this.search
                            }
                        }).then(response => {

                            this.estados = response.data;
                        }).catch(error => {

                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener los estados',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los estados',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }


                },

                async getAllCategorias() {
                    try {
                        axios({
                            method: 'get',
                            url: '/allCategorias',
                            params: {
                                //page: this.page,
                                //per_page: this.per_page,
                                //search: this.search
                            }
                        }).then(response => {

                            this.categorias = response.data;
                        }).catch(error => {

                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener las categorias',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las categorias',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },

                async getAllProveedores() {
                    try {
                        axios({
                            method: 'get',
                            url: '/allProveedores',
                            params: {
                                //page: this.page,
                                //per_page: this.per_page,
                                //search: this.search
                            }
                        }).then(response => {

                            this.proveedores = response.data;
                        }).catch(error => {

                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener los proveedores',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los proveedores',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                },

                async getAllUnidades() {
                    try {
                        axios({
                            method: 'get',
                            url: '/allUnidades',
                            params: {
                                //page: this.page,
                                //per_page: this.per_page,
                                //search: this.search
                            }
                        }).then(response => {

                            this.unidades = response.data;
                        }).catch(error => {

                            swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener las unidades',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }).finally(() => {
                            this.loading = false;
                        })

                    } catch (error) {
                        swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las unidades',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                        this.loading = false;
                    }
                },



            },
            mounted() {

                this.getAllEstados();
                this.getAllCategorias();
                this.getAllProveedores();
                this.getAllUnidades();


            }
        });
    </script>
@endsection
