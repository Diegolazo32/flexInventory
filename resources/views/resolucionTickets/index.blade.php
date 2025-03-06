@extends('Layouts.Navigation')

@section('title', 'Resolución de tickets')

@section('content')

    <div id="app">

        <!-- Resolucion -->
        <div class="card hoverCard">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <h3 class="card-title">Resolución</h3>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createResolucionModal">
                    <i class="fas fa-plus"></i></button>
            </div>


            <div class="card-body">
                <div class="row">

                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Tabla -->
                    <table v-if="!loading" ref="table" class="table table-striped table-hover"
                        style="text-align: center;">
                        <thead>
                            <tr>
                                <th scope="col">Codigo</th>
                                <th scope="col">Serie</th>
                                <th scope="col">Desde</th>
                                <th scope="col">Hasta</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Autorización</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- vue foreach -->
                            <tr v-for="resolucion in resoluciones" :key="resolucion.id">
                                <td>@{{ resolucion.resolucion }}</td>
                                <td>@{{ resolucion.serie }}</td>
                                <td>@{{ resolucion.desde }}</td>
                                <td>@{{ resolucion.hasta }}</td>
                                <td>@{{ parseDate(resolucion.fecha) }}</td>
                                <td>@{{ resolucion.autorizacion }}</td>
                                <td v-if="resolucion.estado == 1">
                                    <span class="badge bg-success">Activo</span>
                                </td>
                                <td v-else>
                                    <span class="badge bg-danger">Inactivo</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!--Create modal-->
        <div class="modal fade" id="createResolucionModal" tabindex="-1" aria-labelledby="createResolucionModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down"
                style="min-height: 220px;">
                <div class="modal-content" style="min-height: auto;">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="createResolucionModalLabel">Crear nueva resolucion</h1>
                        <small class="text-muted">Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios.</small>
                        <small class="text-danger">Al crear una nueva resolución, se desactivará la resolución
                            anterior.</small>
                    </div>
                    <div class="modal-body" style="text-align: justify; height: 320px;">
                        <div class="row">
                            <!--Resolucion-->
                            <div class="col-lg-12">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="text" class="form-control" id="resolucion" name="resolucion"
                                            placeholder="Numero de resolucion" v-model="item.resolucion"
                                            @keyup="validateForm">
                                        <label for="floatingInput">Resolución<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.resolucion">
                                            @{{ errors.resolucion }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Serie-->
                            <div class="col-lg-12">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="text" class="form-control" id="serie" name="serie"
                                            placeholder="Serie de la resolucion" v-model="item.serie" @keyup="validateForm">
                                        <label for="floatingInput">Serie<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.serie">
                                            @{{ errors.serie }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Desde-->
                            <div class="col-lg-6">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="text" class="form-control" id="desde" name="desde"
                                            placeholder="Desde" v-model="item.desde" @keyup="validateForm">
                                        <label for="floatingInput">Desde<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.desde">
                                            @{{ errors.desde }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Hasta-->
                            <div class="col-lg-6">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="text" class="form-control" id="hasta" name="hasta"
                                            placeholder="Hasta" v-model="item.hasta" @keyup="validateForm">
                                        <label for="floatingInput">Hasta<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.hasta">
                                            @{{ errors.hasta }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Fecha-->
                            <div class="col-lg-6">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="date" class="form-control" id="fecha" name="fecha"
                                            placeholder="Fecha de la resolucion" v-model="item.fecha"
                                            @keyup="validateForm">
                                        <label for="floatingInput">Fecha<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.fecha">
                                            @{{ errors.fecha }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Autorizacion-->
                            <div class="col-lg-6">
                                <div class="form-floating col-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-12">
                                        <input type="text" class="form-control" id="autorizacion" name="autorizacion"
                                            placeholder="Autorizacion de la resolucion" v-model="item.autorizacion"
                                            @keyup="validateForm">
                                        <label for="floatingInput">Autorización<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.autorizacion">
                                            @{{ errors.autorizacion }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton"
                            @click="cleanForm">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="createButton"
                            @click="sendForm">Guardar</button>
                    </div>
                </div>
            </div>

        </div>

        <script>
            new Vue({
                el: '#app',
                data: {
                    errors: {},
                    item: {
                        resolucion: null,
                        serie: null,
                        desde: null,
                        hasta: null,
                        fecha: null,
                        estado: null,
                        autorizacion: null,
                    },
                    resoluciones: [],
                    loading: true,

                },
                methods: {
                    validateForm() {
                        this.errors = {};
                        if (!this.item.resolucion) {
                            this.errors.resolucion = 'El campo resolucion es obligatorio';
                            document.getElementById('resolucion').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('resolucion').setAttribute('class', 'form-control is-valid');
                        }

                        if (!this.item.serie) {
                            this.errors.serie = 'El campo serie es obligatorio';
                            document.getElementById('serie').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('serie').setAttribute('class', 'form-control is-valid');
                        }

                        if (!this.item.desde) {
                            this.errors.desde = 'El campo desde es obligatorio';
                            document.getElementById('desde').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('desde').setAttribute('class', 'form-control is-valid');
                        }

                        if (this.item.desde < 0) {
                            this.errors.desde = 'El campo desde no puede ser menor a 0';
                            document.getElementById('desde').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('desde').setAttribute('class', 'form-control is-valid');
                        }

                        if (!this.item.hasta) {
                            this.errors.hasta = 'El campo hasta es obligatorio';
                            document.getElementById('hasta').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('hasta').setAttribute('class', 'form-control is-valid');
                        }

                        if (this.item.hasta < 0) {
                            this.errors.hasta = 'El campo hasta no puede ser menor a 0';
                            document.getElementById('hasta').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('hasta').setAttribute('class', 'form-control is-valid');
                        }

                        if (this.item.hasta <= this.item.desde) {
                            this.errors.hasta = 'El campo hasta no puede ser menor al campo desde';
                            document.getElementById('hasta').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('hasta').setAttribute('class', 'form-control is-valid');
                        }

                        if (!this.item.fecha) {
                            this.errors.fecha = 'El campo fecha es obligatorio';
                            document.getElementById('fecha').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('fecha').setAttribute('class', 'form-control is-valid');
                        }

                        if (!this.item.autorizacion) {
                            this.errors.autorizacion = 'El campo autorizacion es obligatorio';
                            document.getElementById('autorizacion').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('autorizacion').setAttribute('class', 'form-control is-valid');
                        }


                    },
                    cleanForm() {
                        this.item = {
                            resolucion: null,
                            serie: null,
                            desde: null,
                            hasta: null,
                            fecha: null,
                            estado: null,
                            autorizacion: null,
                        }
                        this.errors = {};
                        document.getElementById('resolucion').setAttribute('class', 'form-control');
                        document.getElementById('serie').setAttribute('class', 'form-control');
                        document.getElementById('desde').setAttribute('class', 'form-control');
                        document.getElementById('hasta').setAttribute('class', 'form-control');
                        document.getElementById('fecha').setAttribute('class', 'form-control');
                        document.getElementById('autorizacion').setAttribute('class', 'form-control');
                    },
                    sendForm() {
                        this.validateForm();

                        if (Object.keys(this.errors).length === 0) {

                            //Disable button
                            document.getElementById('createButton').innerHTML =
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Guardando...';
                            document.getElementById('createButton').disabled = true;
                            document.getElementById('cancelButton').disabled = true;

                            axios({
                                method: 'post',
                                url: '/tickets/store',
                                data: this.item
                            }).then(response => {
                                if (response.data.success) {
                                    swal.fire({
                                        toast: true,
                                        showCloseButton: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        title: 'Turno iniciado',
                                        text: response.data.success,
                                        icon: 'success',
                                        confirmButtonText: 'Aceptar',
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
                                        text: response.data.error,
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }

                            }).catch(error => {
                                swal.fire({
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al intentar guardar la resolución',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }).finally(() => {
                                this.resoluciones = [];
                                document.getElementById('createButton').innerHTML =
                                    'Guardar';
                                document.getElementById('createButton').disabled = false;
                                document.getElementById('cancelButton').disabled = false;
                                this.cleanForm();
                                document.getElementById('cancelButton').click();

                                this.getAllResoluciones();
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
                                showCloseButton: true,
                            });
                        }
                    },
                    async getAllResoluciones() {
                        this.loading = true;
                        await axios.get('/allResoluciones')
                            .then(response => {
                                this.resoluciones = response.data;
                                console.log(this.resoluciones);
                            })
                            .catch(error => {
                                console.log(error);
                            });

                        this.loading = false;
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
                    this.getAllResoluciones();

                }
            })
        </script>
    @endsection
