@extends('layouts.Navigation')

@section('title', 'Reporte de productos')

@section('content')
<div id="app">

    <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
        <i class="fas fa-spinner fa-spin"></i> Cargando...
    </div>

    <div class="row" v-if="!loading">
        <div class="card mb-3 col-lg-12">
            <div class="card-body">
                <div class="row">

                <form action="{{ route('reportes.productos.generar') }}" method="POST">
                    @csrf
                    <div class="col-lg-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="estado" name="estado"
                                v-model="reportParams.estado" @blur="validateParams"
                                @change="validateParams">
                                <option v-for="estado in estados" :key="estado.id"
                                    :value="estado.id">
                                    @{{ estado.descripcion }}
                                </option>
                            </select>
                            <label for="floatingInput">Estado</label>
                            <div class="invalid-tooltip" v-if="errors.estado">
                                @{{ errors.estado }}
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-primary" @click="sendForm">Generar reporte</button>
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

            loading: true,

            //Parametros de reporte
            reportParams: {
                estado: 1,
            },

            //Errores
            errors: {},

            //estados
            estados: [],

        },
        methods: {

            validateParams() {

                this.errors = {};

                if (!this.reportParams.estado) {
                    this.errors.estado = 'El estado es requerido';
                    document.getElementById('estado').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('estado').setAttribute('class', 'form-control is-valid');
                }

            },

            //Enviar formulario
            sendForm() {

                this.validateParams();

                if (Object.keys(this.errors).length === 0) {
                    //Generar reporte
                    axios({
                        method: 'post',
                        url: '/reportes/productos/generar',
                        params: {
                            estado: this.reportParams.estado
                        }
                    }).then(response => {
                        swal.fire({
                            title: 'Reporte generado',
                            text: 'El reporte se ha generado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }).catch(error => {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al generar el reporte',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    })
                } else {
                    swal.fire({
                        title: 'Error',
                        text: 'Por favor, complete los campos requeridos',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
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
                        this.loading = false;
                        this.estados = response.data;
                    }).catch(error => {
                        this.loading = false;
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener los estados',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    })

                } catch (error) {
                    swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error al obtener los estados',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }


            },

        },
        mounted() {

            this.getAllEstados();

        }
    });
</script>
@endsection
