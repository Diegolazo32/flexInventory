@extends('Layouts.Navigation')

@section('title', 'Reporte de movimientos de Kardex')

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
                    <h1>Reporte de movimientos</h1>
                </div>


                <div class="card-body">
                    <form action="{{ route('reportes.movimientos.generar') }}" method="POST" id="formReporte"
                        @submit.prevent="sendForm">
                        @csrf
                        <div class="row" style="display: flex; justify-content: space-between;">
                            <div class="col-lg-3">
                                <!--Inventario-->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="inventario" name="inventario"
                                        v-model="reportParams.inventario" @blur="validateParams" @change="validateParams">
                                        <option value="0">Todos</option>

                                        <option v-for="inventario in inventarios" :key="inventario.id"
                                            :value="inventario.id">
                                            @{{ inventario.id }}
                                        </option>
                                    </select>
                                    <label for="floatingInput">Inventario N°</label>
                                    <div class="invalid-tooltip" v-if="errors.inventario">
                                        @{{ errors.inventario }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!--Fecha inicio-->
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                        disabled v-model="reportParams.fecha_inicio" @blur="validateParams">
                                    <label for="floatingInput">Fecha inicio</label>
                                    <div class="invalid-tooltip" v-if="errors.fecha_inicio">
                                        @{{ errors.fecha_inicio }}
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-3">

                                <!--Fecha fin-->
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" disabled
                                        v-model="reportParams.fecha_fin" @blur="validateParams">
                                    <label for="floatingInput">Fecha fin</label>
                                    <div class="invalid-tooltip" v-if="errors.fecha_fin">
                                        @{{ errors.fecha_fin }}
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-3">

                                <!--Accion-->
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="accion" name="accion" v-model="reportParams.accion"
                                        @blur="validateParams">
                                        <option value="0">Todos</option>
                                        <option value="1">Entrada</option>
                                        <option value="2">Salida</option>
                                    </select>
                                    <label for="floatingInput">Accion</label>
                                    <div class="invalid-tooltip" v-if="errors.accion">
                                        @{{ errors.accion }}
                                    </div>
                                </div>

                            </div>

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
                    inventario: 0,
                    fecha_inicio: '',
                    fecha_fin: '',
                    accion: 0,
                },

                //Errores
                errors: {},

                //inventario
                inventarios: [],


            },
            methods: {

                validateParams() {

                    this.errors = {};

                    //If fecha inicio es menor a fecha fin intercambiar valores
                    if (this.reportParams.fecha_inicio && this.reportParams.fecha_fin) {
                        if (this.reportParams.fecha_inicio > this.reportParams.fecha_fin) {
                            let temp = this.reportParams.fecha_inicio;
                            this.reportParams.fecha_inicio = this.reportParams.fecha_fin;
                            this.reportParams.fecha_fin = temp;
                        }
                    }

                    //Validar fecha inicio

                    document.getElementById('inventario').setAttribute('class', 'form-control is-valid');
                    document.getElementById('fecha_inicio').setAttribute('class', 'form-control is-valid');
                    document.getElementById('fecha_fin').setAttribute('class', 'form-control is-valid');
                    document.getElementById('accion').setAttribute('class', 'form-control is-valid');

                },

                //Enviar formulario
                sendForm() {

                    this.validateParams();

                    //sendForm
                    if (Object.keys(this.errors).length === 0) {
                        document.getElementById('formReporte').submit();
                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Reporte',
                            text: 'Generando reporte',
                            icon: 'info',
                        });

                    } else {
                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                        });
                    }

                },

                //Recursos
                async getAllInventarios() {

                    try {
                        axios({
                            method: 'get',
                            url: '/allInventarios',
                            params: {
                                //page: this.page,
                                //per_page: this.per_page,
                                //search: this.search
                            }
                        }).then(response => {

                            this.inventarios = response.data;
                        }).catch(error => {

                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener los inventarios',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {
                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los inventarios',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }

                    this.loading = false;


                },


            },
            mounted() {

                this.getAllInventarios();

            }
        });
    </script>
@endsection
