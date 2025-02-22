@extends('layouts.Navigation')

@section('title', 'Mi negocio')

@section('content')
    <div id="app">

        <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
            <i class="fas fa-spinner fa-spin"></i> Cargando...
        </div>


        <div class="card hoverCard" v-if="!loading">
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
            <!-- Tarjeta de datos -->
            <div class="row">
                <div class="card-body ">
                    <div class=" mb-4 rounded-3">
                        <div class="row">
                            <div class="col-lg-4"
                                style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                                <dvi>
                                    <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo de la empresa"
                                        style="height: 250px; width: 250px; border-radius: 15px; margin-bottom: 20px; object-fit: cover; border: #000000 thin solid;">
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
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
                                            placeholder="Nombre" @keyup="validateForm" v-model="empresa.nombre"
                                            maxlength="100">
                                        <label for="floatingInput">Nombre*</label>
                                        <div class="invalid-tooltip" v-if="errors.nombre">
                                            @{{ errors.nombre }}
                                        </div>
                                    </div>
                                </div>

                                <!--Direccion-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="direccion" name="direccion"
                                            placeholder="Direccion de la empresa" @keyup="validateForm"
                                            v-model="empresa.direccion" maxlength="200">
                                        <label for="floatingInput">Direccion*</label>
                                        <div class="invalid-tooltip" v-if="errors.direccion">@{{ errors.direccion }}</div>
                                    </div>
                                </div>

                                <!--Telefono-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefono" name="telefono"
                                            maxlength="14" placeholder="Telefono de la empresa" @keyup="validateForm"
                                            v-model="empresa.telefono">
                                        <label for="floatingInput">Telefono*</label>
                                        <div class="invalid-tooltip" v-if="errors.telefono">@{{ errors.telefono }}</div>
                                    </div>
                                </div>

                                <!-- email -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Correo electronico de la empresa" @keyup="validateForm"
                                            v-model="empresa.email" maxlength="100">
                                        <label for="floatingInput">Correo electronico</label>
                                        <div class="invalid-tooltip" v-if="errors.email">@{{ errors.email }}</div>
                                    </div>
                                </div>

                                <!-- NIT -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="NIT" name="NIT"
                                            placeholder="NIT de la empresa" @keyup="validateForm" v-model="empresa.NIT"
                                            maxlength="17"> <label for="floatingInput">NIT</label>
                                        <div class="invalid-tooltip" v-if="errors.NIT">@{{ errors.NIT }}</div>
                                    </div>
                                </div>

                                <!-- NRC -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="NRC" name="NRC"
                                            maxlength="24" placeholder="NRC de la empresa" @keyup="validateForm"
                                            v-model="empresa.NRC">
                                        <label for="floatingInput">NRC</label>
                                        <div class="invalid-tooltip" v-if="errors.NRC">@{{ errors.NRC }}</div>
                                    </div>
                                </div>

                                <!-- Giro -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="giro" name="giro"
                                            placeholder="Giro de la empresa" @keyup="validateForm" v-model="empresa.giro"
                                            maxlength="100">
                                        <label for="floatingInput">Giro</label>
                                        <div class="invalid-tooltip" v-if="errors.giro">@{{ errors.giro }}</div>
                                    </div>
                                </div>

                                <!-- Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="representante"
                                            name="representante" placeholder="Representante de la empresa"
                                            @keyup="validateForm" v-model="empresa.representante" maxlength="100">
                                        <label for="floatingInput">Representante</label>
                                        <div class="invalid-tooltip" v-if="errors.representante">@{{ errors.representante }}
                                        </div>
                                    </div>
                                </div>

                                <!-- DUI -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="dui" name="dui"
                                            placeholder="DUI del representante" @keyup="validateForm"
                                            v-model="empresa.dui" maxlength="10">
                                        <label for="floatingInput">DUI</label>
                                        <div class="invalid-tooltip" v-if="errors.dui">@{{ errors.dui }}</div>
                                    </div>
                                </div>

                                <!-- NIT Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nitRepresentante"
                                            name="nitRepresentante" placeholder="NIT del representante"
                                            @keyup="validateForm" v-model="empresa.nitRepresentante" maxlength="17">
                                        <label for="floatingInput">NIT del representante</label>
                                        <div class="invalid-tooltip" v-if="errors.nitRepresentante">
                                            @{{ errors.nitRepresentante }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Telefono Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoRepresentante"
                                            name="telefonoRepresentante" placeholder="Telefono del representante"
                                            @keyup="validateForm" v-model="empresa.telefonoRepresentante" maxlength="14">
                                        <label for="floatingInput">Telefono del representante</label>
                                        <div class="invalid-tooltip" v-if="errors.telefonoRepresentante">
                                            @{{ errors.telefonoRepresentante }}</div>
                                    </div>
                                </div>

                                <!-- email Representante -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="emailRepresentante"
                                            name="emailRepresentante" placeholder="Correo electronico del representante"
                                            @keyup="validateForm" v-model="empresa.emailRepresentante" maxlength="100">
                                        <label for="floatingInput">Correo electronico del representante</label>
                                        <div class="invalid-tooltip" v-if="errors.emailRepresentante">
                                            @{{ errors.emailRepresentante }}</div>
                                    </div>
                                </div>

                                <!-- CuentaContable -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cuentaContable"
                                            name="cuentaContable" placeholder="Cuenta contable" @keyup="validateForm"
                                            v-model="empresa.cuentaContable" maxlength="100">
                                        <label for="floatingInput">Cuenta contable</label>
                                        <div class="invalid-tooltip" v-if="errors.cuentaContable">
                                            @{{ errors.cuentaContable }}</div>
                                    </div>
                                </div>

                                <!-- valorIVA -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="valorIVA" name="valorIVA"
                                            placeholder="Valor IVA" @keyup="validateForm" v-model="empresa.valorIVA"
                                            max="100">
                                        <label for="floatingInput">Valor IVA</label>
                                        <div class="invalid-tooltip" v-if="errors.valorIVA">@{{ errors.valorIVA }}</div>
                                    </div>
                                </div>

                                <!--Logo-->
                                <div class="form col-lg-6" style="margin-bottom: 10px;" position-relative>
                                    <div class="invalid-tooltip" v-if="errors.logo">@{{ errors.logo }}</div>
                                    <input type="file" class="form-control" id="logo" name="logo"
                                        placeholder="Logo de la empresa" @change="validateForm" v-model="empresa.logo"
                                        accept="image/png, image/jpeg, image/jpg">
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
                    cuentaContable: null,
                    valorIVA: null,
                },
                errors: {},
                firstTime: false,
                regex: {
                    telefono: '^\d{4}-\d{4}$',
                }

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

                    let phoneRegex = /[0-9-()+#*]+/g;
                    let phoneRepRegex = /[0-9-()+#*]+/g;
                    let emailRegex = /\S+@\S+\.\S+/;
                    let nameRegex = /^[A-Za-zñÑáéíóúÁÉÍÓÚüÜ'0-9&#., -_]*$/;
                    let duiRegex = /^\d{8}-\d{1}$/;
                    let nitRegex = /^\d{4}-\d{6}-\d{3}-\d{1}$/;


                    //Nombre
                    if (!this.empresa.nombre) {
                        this.errors.nombre = 'El nombre de la empresa es requerido';
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                    } else if (!nameRegex.test(this.empresa.nombre)) {
                        this.errors.nombre = 'El nombre de la empresa no es valido';
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombre').setAttribute('class', 'form-control is-valid');
                    }

                    //Direccion
                    if (!this.empresa.direccion) {
                        this.errors.direccion = 'La direccion de la empresa es requerida';
                        document.getElementById('direccion').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('direccion').setAttribute('class', 'form-control is-valid');
                    }

                    //Telefono
                    if (!this.empresa.telefono) {
                        this.errors.telefono = 'El telefono de la empresa es requerido';
                        document.getElementById('telefono').setAttribute('class', 'form-control is-invalid');
                    } else if (!phoneRegex.test(this.empresa.telefono)) {
                        this.errors.telefono = 'El telefono de la empresa no es valido';
                        document.getElementById('telefono').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('telefono').setAttribute('class', 'form-control is-valid');
                    }

                    //email
                    if (!this.empresa.email) {
                        this.errors.email = 'El correo electronico de la empresa es requerido';
                        document.getElementById('email').setAttribute('class', 'form-control is-invalid');
                    } else if (!this.empresa.email.includes('@') || !this.empresa.email.includes('.')) {
                        this.errors.email = 'El correo electronico no es valido';
                        document.getElementById('email').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('email').setAttribute('class', 'form-control is-valid');
                    }

                    //NIT
                    if (!this.empresa.NIT) {
                        this.errors.NIT = 'El NIT de la empresa es requerido';
                        document.getElementById('NIT').setAttribute('class', 'form-control is-invalid');
                    } else if (!nitRegex.test(this.empresa.NIT)) {
                        this.errors.NIT = 'El NIT de la empresa no es valido';
                        document.getElementById('NIT').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('NIT').setAttribute('class', 'form-control is-valid');
                    }

                    //NRC
                    if (!this.empresa.NRC) {
                        this.errors.NRC = 'El NRC de la empresa es requerido';
                        document.getElementById('NRC').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('NRC').setAttribute('class', 'form-control is-valid');
                    }

                    //Giro
                    if (!this.empresa.giro) {
                        this.errors.giro = 'El giro de la empresa es requerido';
                        document.getElementById('giro').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('giro').setAttribute('class', 'form-control is-valid');
                    }

                    //Representante
                    if (!this.empresa.representante) {
                        this.errors.representante = 'El nombre del representante es requerido';
                        document.getElementById('representante').setAttribute('class', 'form-control is-invalid');
                    } else if (!nameRegex.test(this.empresa.representante)) {
                        this.errors.representante = 'El nombre del representante no es valido';
                        document.getElementById('representante').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('representante').setAttribute('class', 'form-control is-valid');
                    }

                    //nit_representante
                    if (!this.empresa.nitRepresentante) {
                        this.errors.nitRepresentante = 'El NIT del representante es requerido';
                        document.getElementById('nitRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else if (!nitRegex.test(this.empresa.nitRepresentante)) {
                        this.errors.nitRepresentante = 'El NIT del representante no es valido';
                        document.getElementById('nitRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('nitRepresentante').setAttribute('class', 'form-control is-valid');
                    }


                    //telefono_representante
                    if (!this.empresa.telefonoRepresentante) {
                        this.errors.telefonoRepresentante = 'El telefono del representante es requerido';
                        document.getElementById('telefonoRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else if (!phoneRepRegex.test(this.empresa.telefonoRepresentante)) {
                        this.errors.telefonoRepresentante = 'El telefono del representante no es valido';
                        document.getElementById('telefonoRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('telefonoRepresentante').setAttribute('class',
                            'form-control is-valid');
                    }

                    //email_representante
                    if (!this.empresa.emailRepresentante) {
                        this.errors.emailRepresentante = 'El correo electronico del representante es requerido';
                        document.getElementById('emailRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else if (!this.empresa.emailRepresentante.includes('@') || !this.empresa.emailRepresentante
                        .includes('.')) {
                        this.errors.emailRepresentante = 'El correo electronico del representante no es valido';
                        document.getElementById('emailRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('emailRepresentante').setAttribute('class',
                            'form-control is-valid');
                    }


                    //dui
                    if (!this.empresa.dui) {
                        this.errors.dui = 'El DUI del representante es requerido';
                        document.getElementById('dui').setAttribute('class', 'form-control is-invalid');
                    } else if (!duiRegex.test(this.empresa.dui)) {
                        this.errors.dui = 'El DUI del representante no es valido';
                        document.getElementById('dui').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('dui').setAttribute('class', 'form-control is-valid');
                    }

                    //cuentaContable
                    if (!this.empresa.cuentaContable) {
                        this.errors.cuentaContable = 'La cuenta contable es requerida';
                        document.getElementById('cuentaContable').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('cuentaContable').setAttribute('class', 'form-control is-valid');
                    }

                    //valorIVA
                    if (!this.empresa.valorIVA) {
                        this.errors.valorIVA = 'El valor del IVA es requerido';
                        document.getElementById('valorIVA').setAttribute('class', 'form-control is-invalid');
                    } else if (this.empresa.valorIVA < 0 || this.empresa.valorIVA > 100) {
                        this.errors.valorIVA = 'El valor del IVA debe ser un numero entre 0 y 100';
                        document.getElementById('valorIVA').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('valorIVA').setAttribute('class', 'form-control is-valid');
                    }


                    //logo png, jpg, jpeg
                    let logoInput = document.querySelector('#logo');
                    if (logoInput.files.length > 0) {
                        let logo = logoInput.files[0];
                        let logoExtension = logo.name.split('.').pop().toLowerCase();
                        if (logoExtension != 'png' && logoExtension != 'jpg' && logoExtension != 'jpeg') {
                            this.errors.logo = 'El archivo debe ser una imagen en formato PNG, JPG o JPEG';
                            document.getElementById('logo').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('logo').setAttribute('class', 'form-control is-valid');
                        }
                    }

                    //Logo no mas de 2mb
                    if (logoInput.files.length > 0) {
                        let logo = logoInput.files[0];
                        if (logo.size > 2097152) {
                            this.errors.logo = 'El archivo no debe pesar mas de 2MB';
                            document.getElementById('logo').setAttribute('class', 'form-control is-invalid');
                        } else {
                            document.getElementById('logo').setAttribute('class', 'form-control is-valid');
                        }
                    }


                },
                sendForm() {

                    this.validateForm();

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
                    formData.append('cuentaContable', this.empresa.cuentaContable);
                    formData.append('valorIVA', this.empresa.valorIVA);

                    // Agregar el archivo si existe
                    const logoInput = document.querySelector('#logo');
                    if (logoInput.files.length > 0) {
                        formData.append('logo', logoInput.files[0]);
                    }

                    if (this.empresaInfo.id == null) {
                        this.firstTime = true;
                        formData.append('firstTime', this.firstTime);
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
                                        this.firstTime = false;
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
                                    this.firstTime = false;
                                    document.getElementById('cancelButton').click();
                                    this.cleanForm();
                                    this.empresaInfo = [];
                                    this.getAllEmpresa();
                                });

                        } catch (error) {
                            swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al actualizar la información de la empresa.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar',
                            });
                        }
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                        });
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
                    this.empresa.cuentaContable = null;
                    this.empresa.valorIVA = null;

                    this.errors = {};

                    document.getElementById('nombre').setAttribute('class', 'form-control');
                    document.getElementById('direccion').setAttribute('class', 'form-control');
                    document.getElementById('telefono').setAttribute('class', 'form-control');
                    document.getElementById('email').setAttribute('class', 'form-control');
                    document.getElementById('NIT').setAttribute('class', 'form-control');
                    document.getElementById('NRC').setAttribute('class', 'form-control');
                    document.getElementById('giro').setAttribute('class', 'form-control');
                    document.getElementById('representante').setAttribute('class', 'form-control');
                    document.getElementById('dui').setAttribute('class', 'form-control');
                    document.getElementById('nitRepresentante').setAttribute('class', 'form-control');
                    document.getElementById('telefonoRepresentante').setAttribute('class', 'form-control');
                    document.getElementById('emailRepresentante').setAttribute('class', 'form-control');
                    document.getElementById('logo').setAttribute('class', 'form-control');
                    document.getElementById('cuentaContable').setAttribute('class', 'form-control');
                    document.getElementById('valorIVA').setAttribute('class', 'form-control');

                },
                fillData() {
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
                    this.empresa.cuentaContable = this.empresaInfo.cuentaContable;
                    this.empresa.valorIVA = this.empresaInfo.valorIVA;
                }

            },
            mounted() {
                this.getAllEmpresa();
            }
        });
    </script>
@endsection
