@extends('layouts.Navigation')

@section('title', 'Mi negocio')

@section('content')
    <div id="app">

        <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
            <i class="fas fa-spinner fa-spin"></i> Cargando...
        </div>


        <div class="card" v-if="!loading">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Mi negocio</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editEmpresaModal" style="height: 40px;" @click="fillData">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Tabla de productos -->
            <div class="row">
                <div class="card-body">
                    <div class=" mb-4 rounded-3">
                        <div class="row">
                            <div class="col-lg-4"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <dvi>
                                    <img src="{{ asset('storage/logo/logo_empresa.jpg') }}" alt="Logo de la empresa"
                                        style=" height: 250px; width: 250px; border-radius: 15px; border: ; margin-bottom: 20px;">
                                </dvi>
                                <div>

                                    <p class="display-6 fw-bold" style="text-align: center; margin-bottom: 10px;">
                                        @{{ empresaInfo.nombre ?? '-' }}</p>
                                </div>

                            </div>
                            <div class="col-lg-4"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <h1 class="display-6 fw-bold"> Detalles </h1>
                                <label for="direccion" class="fw-bold col-lg-12 fs-4">Giro:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.giro ?? '-' }}</p>
                                <label for="direccion" class="fw-bold col-lg-12 fs-4">Direccion:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.direccion ?? '-' }}</p>
                                <label for="NIT" class="fw-bold col-lg-12 fs-4">NIT:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.NIT ?? '-' }}</p>
                                <label for="NRC" class="fw-bold col-lg-12 fs-4">NRC:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.NRC ?? '-' }}</p>
                                <label for="telefono" class="fw-bold col-lg-12 fs-4">Telefono:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.telefono ?? '-' }}</p>
                                <label for="email" class="fw-bold col-lg-12 fs-4">Correo electronico:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.email ?? '-' }}</p>
                                <hr>
                                <!--<button class="btn btn-primary btn-lg" type="button">Example button</button>-->
                            </div>
                            <div class="col-lg-4"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <h1 class="display-6 fw-bold"> Representante </h1>
                                <label for="direccion" class="fw-bold col-lg-12 fs-4">Nombre:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.representante ?? '-' }}</p>
                                <label for="direccion" class="fw-bold col-lg-12 fs-4">DUI:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.dui ?? '-' }}</p>
                                <label for="NIT" class="fw-bold col-lg-12 fs-4">NIT:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.nit_representante ?? '-' }}</p>
                                <label for="telefono" class="fw-bold col-lg-12 fs-4">Telefono:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.telefono_representante ?? '-' }}</p>
                                <label for="email" class="fw-bold col-lg-12 fs-4">Correo electronico:</label>
                                <p class="col-lg-12 fs-5">@{{ empresaInfo.email_representante ?? '-' }}</p>
                                <br>
                                <br>
                                <hr>
                                <!--<button class="btn btn-primary btn-lg" type="button">Example button</button>-->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="modal fade" id="editEmpresaModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down mdl">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearProductoModalLabel">Editar informacion </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('productos.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!--Nombre-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" @keyup="validateForm"
                                            v-model="empresa.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger" v-if="errors.nombre">@{{ errors.nombre }}</small>
                                    </div>
                                </div>

                                <!--Direccion-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="direccion" name="direccion"
                                            placeholder="Direccion de la empresa" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.direccion">
                                        <label for="floatingInput">Direccion*</label>
                                        <small class="text-danger" v-if="errors.direccion">@{{ errors.direccion }}</small>
                                    </div>
                                </div>

                                <!--Telefono-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefono" name="telefono"
                                            :mask="['(###) ####-####']" placeholder="Telefono de la empresa"
                                            @blur="validateForm()" @keyup="validateForm()" v-model="empresa.telefono">
                                        <label for="floatingInput">Telefono*</label>
                                        <small class="text-danger" v-if="errors.telefono">@{{ errors.telefono }}</small>
                                    </div>
                                </div>

                                <!-- email -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Correo electronico de la empresa" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.email">
                                        <label for="floatingInput">Correo electronico</label>
                                        <small class="text-danger" v-if="errors.email">@{{ errors.email }}</small>
                                    </div>
                                </div>

                                <!-- NIT -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="NIT" name="NIT"
                                            placeholder="NIT de la empresa" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.NIT">
                                        <label for="floatingInput">NIT</label>
                                        <small class="text-danger" v-if="errors.NIT">@{{ errors.NIT }}</small>
                                    </div>
                                </div>

                                <!-- NRC -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="NRC" name="NRC"
                                            placeholder="NRC de la empresa" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.NRC">
                                        <label for="floatingInput">NRC</label>
                                        <small class="text-danger" v-if="errors.NRC">@{{ errors.NRC }}</small>
                                    </div>
                                </div>

                                <!-- Giro -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="giro" name="giro"
                                            placeholder="Giro de la empresa" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.giro">
                                        <label for="floatingInput">Giro</label>
                                        <small class="text-danger" v-if="errors.giro">@{{ errors.giro }}</small>
                                    </div>
                                </div>

                                <!-- Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="representante"
                                            name="representante" placeholder="Representante de la empresa"
                                            @blur="validateForm()" @keyup="validateForm()"
                                            v-model="empresa.representante">
                                        <label for="floatingInput">Representante</label>
                                        <small class="text-danger"
                                            v-if="errors.representante">@{{ errors.representante }}</small>
                                    </div>
                                </div>

                                <!-- DUI -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="dui" name="dui"
                                            placeholder="DUI del representante" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.dui">
                                        <label for="floatingInput">DUI</label>
                                        <small class="text-danger" v-if="errors.dui">@{{ errors.dui }}</small>
                                    </div>
                                </div>

                                <!-- NIT Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nitRepresentante"
                                            name="nitRepresentante" placeholder="NIT del representante"
                                            @blur="validateForm()" @keyup="validateForm()"
                                            v-model="empresa.nitRepresentante">
                                        <label for="floatingInput">NIT del representante</label>
                                        <small class="text-danger" v-if="errors.nitRepresentante">@{{ errors.nitRepresentante }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Telefono Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoRepresentante"
                                            name="telefonoRepresentante" :mask="['(###) ####-####']"
                                            placeholder="Telefono del representante" @blur="validateForm()"
                                            @keyup="validateForm()" v-model="empresa.telefonoRepresentante">
                                        <label for="floatingInput">Telefono del representante</label>
                                        <small class="text-danger"
                                            v-if="errors.telefonoRepresentante">@{{ errors.telefonoRepresentante }}</small>
                                    </div>
                                </div>

                                <!-- email Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="emailRepresentante"
                                            name="emailRepresentante" placeholder="Correo electronico del representante"
                                            @blur="validateForm()" @keyup="validateForm()"
                                            v-model="empresa.emailRepresentante">
                                        <label for="floatingInput">Correo electronico del representante</label>
                                        <small class="text-danger"
                                            v-if="errors.emailRepresentante">@{{ errors.emailRepresentante }}</small>
                                    </div>
                                </div>


                                <!--Logo-->
                                <div class="form col-lg-6" style="margin-bottom: 10px;">

                                    <input type="file" class="form-control" id="logo" name="logo"
                                        placeholder="Logo de la empresa" @blur="validateForm()" @keyup="validateForm()"
                                        v-model="empresa.logo">
                                    <small class="text-danger" v-if="errors.logo">@{{ errors.logo }}</small>

                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton"
                                @click="cleanForm">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="SubmitForm"
                                @click="sendForm">Guardar</button>
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
                empresaInfo: [],
                empresa: {
                    nombre: null,
                    direccion: null,
                    telefono: null,
                    email: null,
                    logo: null,
                    NIT: null,
                    NRC: null,
                    giro: null,
                    representante: null,
                    dui: null,
                    nitRepresentante: null,
                    telefonoRepresentante: null,
                    emailRepresentante: null,
                },
                errors: {}

            },
            methods: {
                async getAllEmpresa() {
                    this.loading = true;
                    await axios.get('/allEmpresa')
                        .then(response => {

                            if (response.data.length == 0) {
                                this.loading = false;
                                return;
                            }

                            this.empresaInfo = response.data[0];
                            this.loading = false;

                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                validateForm() {
                    this.errors = {};


                    //Nombre
                    if (!this.empresa.nombre) {
                        this.errors.nombre = 'El nombre de la empresa es requerido';
                        document.getElementById('nombre').style.borderColor = 'red';
                    } else {
                        document.getElementById('nombre').style.borderColor = 'green';
                    }

                    //Direccion
                    if (!this.empresa.direccion) {
                        this.errors.direccion = 'La direccion de la empresa es requerida';
                        document.getElementById('direccion').style.borderColor = 'red';
                    } else {
                        document.getElementById('direccion').style.borderColor = 'green';
                    }

                    //Telefono
                    if (!this.empresa.telefono) {
                        this.errors.telefono = 'El telefono de la empresa es requerido';
                        document.getElementById('telefono').style.borderColor = 'red';
                    } else {
                        document.getElementById('telefono').style.borderColor = 'green';
                    }

                    //email
                    if (!this.empresa.email) {
                        this.errors.email = 'El correo electronico de la empresa es requerido';
                        document.getElementById('email').style.borderColor = 'red';
                    } else {
                        document.getElementById('email').style.borderColor = 'green';
                    }

                    //NIT
                    if (!this.empresa.NIT) {
                        this.errors.NIT = 'El NIT de la empresa es requerido';
                        document.getElementById('NIT').style.borderColor = 'red';
                    } else {
                        document.getElementById('NIT').style.borderColor = 'green';
                    }

                    //NRC
                    if (!this.empresa.NRC) {
                        this.errors.NRC = 'El NRC de la empresa es requerido';
                        document.getElementById('NRC').style.borderColor = 'red';
                    } else {
                        document.getElementById('NRC').style.borderColor = 'green';
                    }

                    //Giro
                    if (!this.empresa.giro) {
                        this.errors.giro = 'El giro de la empresa es requerido';
                        document.getElementById('giro').style.borderColor = 'red';
                    } else {
                        document.getElementById('giro').style.borderColor = 'green';
                    }

                    //Representante
                    if (!this.empresa.representante) {
                        this.errors.representante = 'El nombre del representante es requerido';
                        document.getElementById('representante').style.borderColor = 'red';
                    } else {
                        document.getElementById('representante').style.borderColor = 'green';
                    }

                    //nit_representante
                    if (!this.empresa.nitRepresentante) {
                        this.errors.nitRepresentante = 'El NIT del representante es requerido';
                        document.getElementById('nitRepresentante').style.borderColor = 'red';
                    } else {
                        document.getElementById('nitRepresentante').style.borderColor = 'green';
                    }


                    //telefono_representante
                    if (!this.empresa.telefonoRepresentante) {
                        this.errors.telefonoRepresentante = 'El telefono del representante es requerido';
                        document.getElementById('telefonoRepresentante').style.borderColor = 'red';
                    } else {
                        document.getElementById('telefonoRepresentante').style.borderColor = 'green';
                    }

                    //email_representante
                    if (!this.empresa.emailRepresentante) {
                        this.errors.emailRepresentante = 'El correo electronico del representante es requerido';
                        document.getElementById('emailRepresentante').style.borderColor = 'red';
                    } else {
                        document.getElementById('emailRepresentante').style.borderColor = 'green';
                    }


                    //dui
                    if (!this.empresa.dui) {
                        this.errors.dui = 'El DUI del representante es requerido';
                        document.getElementById('dui').style.borderColor = 'red';
                    } else {
                        document.getElementById('dui').style.borderColor = 'green';
                    }


                    //logo
                    document.getElementById('logo').style.borderColor = 'green';


                },
                sendForm() {
                    const formData = new FormData();

                    // Agregar cada campo del formulario
                    formData.append('nombre', this.empresa.nombre);
                    formData.append('direccion', this.empresa.direccion);
                    formData.append('telefono', this.empresa.telefono);
                    formData.append('email', this.empresa.email);
                    formData.append('NIT', this.empresa.NIT);
                    formData.append('NRC', this.empresa.NRC);
                    formData.append('giro', this.empresa.giro);
                    formData.append('representante', this.empresa.representante);
                    formData.append('dui', this.empresa.dui);
                    formData.append('nitRepresentante', this.empresa.nitRepresentante);
                    formData.append('telefonoRepresentante', this.empresa.telefonoRepresentante);
                    formData.append('emailRepresentante', this.empresa.emailRepresentante);

                    // Agregar el archivo si existe
                    const logoInput = document.querySelector('#logo');
                    if (logoInput.files.length > 0) {
                        formData.append('logo', logoInput.files[0]);
                    }

                    if (this.empresaInfo.id == null) {
                        this.empresaInfo.id = 0;
                    }

                    if (Object.keys(this.errors).length === 0) {
                        try {
                            // Realizar el request con Axios
                            axios.post('/empresa/edit/' + this.empresaInfo.id, formData, {
                                    headers: {
                                        'Content-Type': 'multipart/form-data',
                                    },
                                })
                                .then(response => {
                                    if (response.data.success) {
                                        swal.fire({
                                            title: 'Cambios guardados',
                                            text: response.data.success,
                                            icon: 'success',
                                        });
                                        window.location.reload();
                                    } else {
                                        swal.fire({
                                            title: 'Error',
                                            text: response.data.error,
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar',
                                        });
                                    }
                                })
                                .catch(error => {
                                    swal.fire({
                                        title: 'Error',
                                        text: 'Ocurrió un error al actualizar la información de la empresa.',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar',
                                    });
                                }).finally(() => {
                                    document.getElementById('cancelButton').click();
                                });
                        } catch (error) {
                            swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al actualizar la información de la empresa.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar',
                            });
                        }
                    }
                },
                cleanForm() {

                    this.empresa.nombre = null;
                    this.empresa.direccion = null;
                    this.empresa.telefono = null;
                    this.empresa.email = null;
                    this.empresa.logo = null;
                    this.empresa.NIT = null;
                    this.empresa.NRC = null;
                    this.empresa.giro = null;
                    this.empresa.representante = null;
                    this.empresa.dui = null;
                    this.empresa.nitRepresentante = null;
                    this.empresa.telefonoRepresentante = null;
                    this.empresa.emailRepresentante = null;
                    this.empresa.logo = '';

                },
                fillData() {

                    console.log('llenando datos');

                    this.empresa.nombre = this.empresaInfo.nombre;
                    this.empresa.direccion = this.empresaInfo.direccion;
                    this.empresa.telefono = this.empresaInfo.telefono;
                    this.empresa.email = this.empresaInfo.email;
                    this.empresa.NIT = this.empresaInfo.NIT;
                    this.empresa.NRC = this.empresaInfo.NRC;
                    this.empresa.giro = this.empresaInfo.giro;
                    this.empresa.representante = this.empresaInfo.representante;
                    this.empresa.dui = this.empresaInfo.dui;
                    this.empresa.nitRepresentante = this.empresaInfo.nit_representante;
                    this.empresa.telefonoRepresentante = this.empresaInfo.telefono_representante;
                    this.empresa.emailRepresentante = this.empresaInfo.email_representante;
                    this.empresa.logo = '';
                }

            },
            mounted() {
                this.getAllEmpresa();
            }
        });
    </script>
@endsection
