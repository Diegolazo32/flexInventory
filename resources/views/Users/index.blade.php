@extends('layouts.Navigation')

@section('title', 'Usuarios')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-10">
                        <h1>Usuarios</h1>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-md-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUserModal"
                            style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editUserModalBtn"
                            data-bs-target="#editUserModal" style="height: 40px;" hidden>
                            Editar usuario
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteUserModalBtn"
                            data-bs-target="#deleteUserModal" style="height: 40px;" hidden>
                            Eliminar usuario
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="revertModalBtn"
                            data-bs-target="#revertModal" style="height: 40px;" hidden>
                            Revertir contraseña
                        </button>
                    </div>
                </div>
            </div>
            <!-- Buscador -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">

                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre"
                                    v-model="search">
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" @click="searchFn">Buscar</button>
                                <button class="btn btn-primary" @click="cleanSearch">Limpiar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de usuarios -->
            <div class="row">
                <div class="card-body">
                    <div class="table-responsive">

                        <!-- A LA GRAN MADRE CON VUE -->
                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">DUI</th>
                                    <th scope="col">Fecha de nacimiento</th>
                                    <th scope="col">Edad</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col">Fecha de creacion</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Contraseña</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='usuario in usuarios' :key="usuario.id">
                                    <td v-if="usuario.nombre.length > 10">
                                        @{{ usuario.nombre.substring(0, 10) }}...
                                    </td>
                                    <td v-else>
                                        @{{ usuario.nombre }}
                                    </td>
                                    <td>@{{ usuario.apellido }}</td>
                                    <td v-if="usuario.DUI">
                                        @{{ usuario.DUI }}
                                    </td>
                                    <td v-else>
                                        --
                                    </td>
                                    <td>
                                        @{{ formatDate(usuario.fechaNacimiento) }}
                                    </td>
                                    <td v-if="usuario.edad">
                                        @{{ usuario.edad }}
                                    </td>
                                    <td v-else>
                                        --
                                    </td>
                                    <td v-if="usuario.genero == 1">
                                        Masculino
                                    </td>
                                    <td v-else>
                                        Femenino
                                    </td>
                                    <td>@{{ formatDate(usuario.created_at) }}</td>
                                    <td>@{{ usuario.usuario }}</td>
                                    <td v-if="usuario.hasPassword == false">
                                        <span class="badge bg-warning">No definida</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-success">Definida</span>
                                    </td>
                                    <td v-if="usuario.rol == 1">
                                        <span class="badge bg-primary">Administrador</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-secondary">Usuario</span>
                                    </td>
                                    <td v-if="usuario.estado == 1">
                                        <span class="badge bg-success">
                                            @{{ estados.find(estado => estado.id == usuario.estado).descripcion }}
                                        </span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger">@{{ estados.find(estado => estado.id == usuario.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editUser(usuario)">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-warning" v-if="usuario.hasPassword == true" id="revertBTN"
                                            @click="openRevertModal(usuario)">
                                            <i class="fas fa-lock"></i>
                                        </button>

                                        <!-- Id del boton asociado al usuario -->
                                        <button class="btn btn-warning" v-else disabled id="revertBTN">
                                            <i class="fas fa-lock"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteUser(usuario)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="12">

                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="crearUserModal" tabindex="-1" aria-labelledby="crearUserModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearUserModalLabel">Crear usuario </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" v-model="item.nombre"
                                            value="{{ old('nombre') }}">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger" v-if="errors.nombre">@{{ errors.nombre }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="apellido" name="apellido"
                                            value="{{ old('apellido') }}" placeholder="Apellido" v-model="item.apellido"
                                            @blur="validateForm">
                                        <label for="floatingInput">Apellido*</label>
                                        <small class="text-danger" v-if="errors.apellido">@{{ errors.apellido }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="DUI" name="DUI"
                                            value="{{ old('DUI') }}" placeholder="DUI" @blur="validateForm"
                                            v-model="item.DUI" maxlength="10"> <label for="floatingInput">DUI*</label>
                                        <small class="text-danger" v-if="errors.DUI">@{{ errors.DUI }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="fechaNacimiento"
                                            name="fechaNacimiento" placeholder="Fecha de nacimiento"
                                            value="{{ old('fechaNacimiento') }}" @change="validateDate"
                                            v-model="item.fechaNacimiento">
                                        <label for="floatingInput">Fecha de nacimiento*</label>
                                        <small class="text-danger"
                                            v-if="errors.fechaNacimiento">@{{ errors.fechaNacimiento }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="genero" name="genero" v-model="item.genero"
                                            @change="validateForm">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                        <label for="floatingInput">Genero*</label>
                                        <small class="text-danger" v-if="errors.genero">@{{ errors.genero }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="usuario" name="usuario"
                                            value="{{ old('usuario') }}" placeholder="Usuario" v-model="item.usuario"
                                            @blur="validateForm" @keyup="validateForm">
                                        <label for="floatingInput">Usuario*</label>
                                        <small class="text-danger" v-if="errors.usuario">@{{ errors.usuario }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="rol" name="rol" v-model="item.rol"
                                            @change="validateForm">
                                            <option value="1">Administrador</option>
                                            <option value="2">Usuario</option>
                                        </select>
                                        <label for="floatingInput">Rol*</label>
                                        <small class="text-danger" v-if="errors.rol">@{{ errors.rol }}</small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="SubmitForm"
                            @click="sendForm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Edit modal-->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editUserModalLabel">Editar usuario</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">

                                    <div class="form-floating mb-3">
                                        <!-- Nombre -->
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.nombre">@{{ editErrors.nombre }}</small>
                                    </div>

                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Apellido -->
                                        <input type="text" class="form-control" id="apellidoEdit" name="apellido"
                                            placeholder="Apellido" v-model="editItem.apellido" @blur="validateEditForm">
                                        <label for="floatingInput">Apellido*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.apellido">@{{ editErrors.apellido }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- DUI -->
                                        <input type="text" class="form-control" id="DUIEdit" name="DUI"
                                            placeholder="DUI" @blur="validateEditForm" v-model="editItem.DUI"
                                            maxlength="10">
                                        <label for="floatingInput">DUI*</label>
                                        <small class="text-danger" v-if="editErrors.DUI">@{{ editErrors.DUI }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Fecha de nacimiento -->
                                        <input type="date" class="form-control" id="fechaNacimientoEdit"
                                            name="fechaNacimiento" placeholder="Fecha de nacimiento" @blur="validateDate"
                                            v-model="editItem.fechaNacimiento">
                                        <label for="floatingInput">Fecha de nacimiento*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.fechaNacimiento">@{{ editErrors.fechaNacimiento }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Genero -->
                                        <select class="form-select" id="generoEdit" name="genero"
                                            v-model="editItem.genero" @change="validateEditForm">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                        <label for="floatingInput">Genero*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.genero">@{{ editErrors.genero }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Usuario -->
                                        <input type="text" class="form-control" id="usuarioEdit" name="usuario"
                                            placeholder="Usuario" v-model="editItem.usuario" @blur="validateEditForm"
                                            @keyup="validateEditForm">
                                        <label for="floatingInput">Usuario*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.usuario">@{{ editErrors.usuario }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Rol -->
                                        <select class="form-select" id="rolEdit" name="rol" v-model="editItem.rol"
                                            @change="validateEditForm">
                                            <option value="1">Administrador</option>
                                            <option value="2">Usuario</option>
                                        </select>
                                        <label for="floatingInput">Rol*</label>
                                        <small class="text-danger" v-if="editErrors.rol">@{{ editErrors.rol }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Estado -->
                                        <select class="form-select" id="estadoEdit" name="estado"
                                            v-model="editItem.estado" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="estado in estados" :key="estado.id"
                                                :value="estado.id">
                                                @{{ estado.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Estado*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.estado">@{{ editErrors.estado }}</small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButtonEdit"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="SubmitFormEdit"
                            @click="sendFormEdit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete modal-->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteUserModalLabel">Eliminar usuario</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este usuario?</small>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h3>Nombre: @{{ deleteItem.nombre }}</h3>
                        <h3>Apellido: @{{ deleteItem.apellido }}</h3>
                        <h3>Usuario: @{{ deleteItem.usuario }}</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="canceldeleteButton"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="deleteButton"
                            @click="sendDeleteForm">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--revert password modal-->
        <div class="modal fade" id="revertModal" tabindex="-1" aria-labelledby="revertModalLabel" aria-hidden="inert"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="revertModalLabel">Restablecer contraseña</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de reestablecer la contraseña a este
                            usuario?</small>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h3>Nombre: @{{ revertItem.nombre }}</h3>
                        <h3>Apellido: @{{ revertItem.apellido }}</h3>
                        <h3>Usuario: @{{ revertItem.usuario }}</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelRevertButton"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-warning" id="revertButton"
                            @click="revertPassword(revertItem)">Reestablecer</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                item: {
                    nombre: '',
                    apellido: '',
                    DUI: '',
                    fechaNacimiento: '',
                    genero: '',
                    usuario: '',
                    rol: '',
                },
                errors: {},
                editErrors: {},
                editItem: {
                    id: '',
                    nombre: '',
                    apellido: '',
                    DUI: '',
                    fechaNacimiento: '',
                    genero: '',
                    usuario: '',
                    rol: '',
                    estado: ''
                },
                deleteItem: {
                    id: '',
                    nombre: '',
                    apellido: '',
                    usuario: '',
                    rol: '',
                    estado: ''
                },
                usuarios: [],
                searchUsuarios: [],
                search: '',
                filtered: [],
                revertItem: {
                    id: '',
                    nombre: '',
                    apellido: '',
                    usuario: '',
                    rol: '',
                    estado: ''
                },
                estados: [],
            },
            methods: {
                validateForm() {
                    this.errors = {};
                    if (!this.item.nombre) {
                        this.errors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombre').style.border = '1px solid red';
                    } else {
                        document.getElementById('nombre').style.border = '1px solid green';
                    }
                    if (!this.item.genero) {
                        this.errors.genero = 'Este campo es obligatorio';
                        document.getElementById('genero').style.border = '1px solid red';
                    } else {
                        document.getElementById('genero').style.border = '1px solid green';
                    }
                    if (!this.item.usuario) {
                        this.errors.usuario = 'Este campo es obligatorio';
                        document.getElementById('usuario').style.border = '1px solid red';
                    } else {
                        document.getElementById('usuario').style.border = '1px solid green';
                    }
                    if (!this.item.rol) {
                        this.errors.rol = 'Este campo es obligatorio';
                        document.getElementById('rol').style.border = '1px solid red';
                    } else {
                        document.getElementById('rol').style.border = '1px solid green';
                    }
                    if (!this.item.fechaNacimiento) {
                        this.errors.fechaNacimiento = 'Este campo es obligatorio';
                        document.getElementById('fechaNacimiento').style.border = '1px solid red';
                    } else {
                        document.getElementById('fechaNacimiento').style.border = '1px solid green';
                    }
                    if (!this.item.apellido) {
                        this.errors.apellido = 'Este campo es obligatorio';
                        document.getElementById('apellido').style.border = '1px solid red';
                    } else {
                        document.getElementById('apellido').style.border = '1px solid green';
                    }
                    if (!this.item.DUI) {
                        this.errors.DUI = 'Este campo es obligatorio';
                        document.getElementById('DUI').style.border = '1px solid red';
                    } else {
                        document.getElementById('DUI').style.border = '1px solid green';
                    }

                    this.validateDate();
                    this.validateUsername();
                },
                validateEditForm() {

                    editErrors = {};

                    this.validateEditDate();
                    this.validateEditUsername();

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('nombreEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.genero) {
                        this.editErrors.genero = 'Este campo es obligatorio';
                        document.getElementById('generoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('generoEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.usuario) {
                        this.editErrors.usuario = 'Este campo es obligatorio';
                        document.getElementById('usuarioEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('usuarioEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.rol) {
                        this.editErrors.rol = 'Este campo es obligatorio';
                        document.getElementById('rolEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('rolEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.fechaNacimiento) {
                        this.editErrors.fechaNacimiento = 'Este campo es obligatorio';
                    }
                    if (!this.editItem.apellido) {
                        this.editErrors.apellido = 'Este campo es obligatorio';
                        document.getElementById('apellidoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('apellidoEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.DUI) {
                        this.editErrors.DUI = 'Este campo es obligatorio';
                        document.getElementById('DUIEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('DUIEdit').style.border = '1px solid green';
                    }

                    //Si el estado es activo se le asigna borde verde al campo
                    if (this.editItem.estado == 1) {
                        document.getElementById('estadoEdit').style.border = '1px solid green';
                    } else {
                        document.getElementById('estadoEdit').style.border = '1px solid red';
                    }


                },
                sendForm() {
                    this.validateForm();

                    if (Object.keys(this.errors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitForm').innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButton').disabled = true;

                        axios({
                            method: 'post',
                            url: '/users/store',
                            data: this.item
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                '<i class="fas fa-save"></i> Guardar';

                            //Cerrar modal
                            document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Usuario creado',
                                    text: 'El usuario ha sido creado correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al crear el usuario',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }

                        }).catch(error => {

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al crear el usuario',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar usuarios
                            this.getAllUsers();
                        })

                    }
                },
                sendFormEdit() {
                    this.validateEditForm();

                    if (Object.keys(this.editErrors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitFormEdit').innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitFormEdit').disabled = true;
                        document.getElementById('cancelButtonEdit').disabled = true;

                        axios({
                            method: 'post',
                            url: '/users/edit/' + this.editItem.id,
                            data: this.editItem
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitFormEdit').innerHTML = 'Guardar';

                            //Cerrar modal
                            document.getElementById('cancelButtonEdit').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Usuario editado',
                                    text: 'El usuario ha sido editado correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al editar el usuario',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }

                        }).catch(error => {

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al editar el usuario',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar usuarios
                            this.getAllUsers();

                        })

                    }
                },
                cleanForm() {
                    this.nombre = '';
                    this.apellido = '';
                    this.DUI = '';
                    this.fechaNacimiento = '';
                    this.genero = '';
                    this.usuario = '';
                    this.rol = '';
                    this.errors = {};
                    this.editErrors = {};
                    //this.search = '';
                    //this.usuarios = [];
                    this.editItem = {
                        id: '',
                        nombre: '',
                        apellido: '',
                        DUI: '',
                        fechaNacimiento: '',
                        genero: '',
                        usuario: '',
                        rol: '',
                        estado: ''
                    };
                    this.deleteItem = {
                        id: '',
                        nombre: '',
                        apellido: '',
                        usuario: '',
                        rol: '',
                        estado: ''
                    };

                    document.getElementById('nombre').style.border = '1px solid #ced4da';
                    document.getElementById('apellido').style.border = '1px solid #ced4da';
                    document.getElementById('DUI').style.border = '1px solid #ced4da';
                    document.getElementById('fechaNacimiento').style.border = '1px solid #ced4da';
                    document.getElementById('genero').style.border = '1px solid #ced4da';
                    document.getElementById('usuario').style.border = '1px solid #ced4da';
                    document.getElementById('rol').style.border = '1px solid #ced4da';

                    document.getElementById('nombreEdit').style.border = '1px solid #ced4da';
                    document.getElementById('apellidoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('DUIEdit').style.border = '1px solid #ced4da';
                    document.getElementById('fechaNacimientoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('generoEdit').style.border = '1px solid #ced4da';
                    document.getElementById('usuarioEdit').style.border = '1px solid #ced4da';
                    document.getElementById('rolEdit').style.border = '1px solid #ced4da';
                    document.getElementById('estadoEdit').style.border = '1px solid #ced4da';

                    //this.getAllUsers();
                    this.usuarios = this.searchUsuarios;
                },
                cleanSearch() {
                    this.search = '';
                    this.usuarios = this.searchUsuarios;
                },
                validateDate() {
                    let date = new Date(this.item.fechaNacimiento);
                    let today = new Date();

                    if (date > today) {
                        this.errors.fechaNacimiento =
                            'La fecha de nacimiento no puede ser mayor a la fecha actual';
                    } else {
                        if (today.getFullYear() - date.getFullYear() < 18) {
                            this.errors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        }
                    }
                },
                validateEditDate() {
                    let date = new Date(this.editItem.fechaNacimiento);
                    let today = new Date();

                    if (date > today) {
                        this.editErrors.fechaNacimiento =
                            'La fecha de nacimiento no puede ser mayor a la fecha actual';
                        document.getElementById('fechaNacimientoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('fechaNacimientoEdit').style.border = '1px solid green';
                    }

                    if (today.getFullYear() - date.getFullYear() < 18) {
                        this.editErrors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        document.getElementById('fechaNacimientoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('fechaNacimientoEdit').style.border = '1px solid green';
                    }

                },
                validateUsername() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9]{5,}$/;

                    if (!regex.test(this.item.usuario)) {
                        this.errors.usuario =
                            'El usuario debe tener al menos 5 caracteres y no contener espacios o caracteres especiales';
                    }

                    for (let i = 0; i < this.usuarios.length; i++) {
                        if (this.usuarios[i].usuario == this.item.usuario) {
                            this.errors.usuario = 'El usuario ya existe';
                        }
                    }
                },
                validateEditUsername() {

                    this.editErrors = {};

                    let regex = /^[a-zA-Z0-9]{5,}$/;

                    if (!regex.test(this.editItem.usuario)) {
                        this.editErrors.usuario =
                            'El usuario debe tener al menos 5 caracteres y no contener espacios o caracteres especiales';
                    }

                    //Eliminar del array el usuario que se esta editando
                    this.usuarios = this.usuarios.filter(user => user.id != this.editItem.id);

                    //recorrer this.usuarios
                    for (let i = 0; i < this.usuarios.length; i++) {
                        if (this.usuarios[i].usuario == this.editItem.usuario) {
                            this.editErrors.usuario = 'El usuario ya existe';
                        }
                    }

                },
                editUser(user) {
                    this.editItem.nombre = user.nombre;
                    this.editItem.apellido = user.apellido;
                    this.editItem.DUI = user.DUI;
                    this.editItem.fechaNacimiento = user.fechaNacimiento;
                    this.editItem.genero = user.genero;
                    this.editItem.usuario = user.usuario;
                    this.editItem.rol = user.rol;
                    this.editItem.estado = user.estado;
                    this.editItem.id = user.id;

                    //dar click al boton de modal
                    document.getElementById('editUserModalBtn').click();

                },
                DeleteUser(user) {
                    this.deleteItem.nombre = user.nombre;
                    this.deleteItem.apellido = user.apellido;
                    this.deleteItem.usuario = user.usuario;
                    this.deleteItem.rol = user.rol;
                    this.deleteItem.estado = user.estado;
                    this.deleteItem.id = user.id;

                    //dar click al boton de modal
                    document.getElementById('deleteUserModalBtn').click();
                },
                sendDeleteForm() {
                    //Inhabilitar botones
                    document.getElementById('deleteButton').disabled = true;
                    document.getElementById('canceldeleteButton').disabled = true;

                    //Cambiar icono de boton
                    document.getElementById('deleteButton').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> Eliminando...';

                    axios({
                        method: 'delete',
                        url: '/users/delete/' + this.deleteItem.id,
                    }).then(response => {


                        if (response.data.error) {
                            //Habilitar boton
                            document.getElementById('deleteButton').disabled = false;
                            document.getElementById('canceldeleteButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('deleteButton').innerHTML = 'Eliminar';

                            //Cerrar modal
                            document.getElementById('canceldeleteButton').click();

                            swal.fire({
                                title: 'Error',
                                text: response.data.error,
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {


                            //Habilitar boton
                            document.getElementById('deleteButton').disabled = false;
                            document.getElementById('canceldeleteButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('deleteButton').innerHTML =
                                '<i class="fas fa-trash"></i>';

                            //Cerrar modal
                            document.getElementById('canceldeleteButton').click();

                            swal.fire({
                                title: 'Usuario eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        }

                    }).catch(error => {

                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al eliminar el usuario',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                        //Habilitar boton
                        document.getElementById('deleteButton').disabled = false;
                        document.getElementById('canceldeleteButton').disabled = false;

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar usuarios
                        this.getAllUsers();
                    })


                },
                openRevertModal(user) {
                    this.revertItem = user;
                    document.getElementById('revertModalBtn').click();
                },
                revertPassword($user) {

                    //change icon to loading
                    document.getElementById('revertButton').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i>';
                    //disable button
                    document.getElementById('revertButton').disabled = true;
                    document.getElementById('cancelRevertButton').disabled = true;

                    axios({
                        url: '/users/rstpsw/' + $user.id,
                        method: 'POST',
                    }).then(response => {

                        //change icon to lock
                        document.getElementById('revertButton').innerHTML =
                            '<i class="fas fa-lock"></i>';

                        //enable button
                        document.getElementById('revertButton').disabled = false;
                        document.getElementById('cancelRevertButton').disabled = false;

                        //close modal
                        document.getElementById('cancelRevertButton').click();

                        swal.fire({
                            title: 'Contraseña reiniciada',
                            text: response.data.success,
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                        });


                    }).catch(error => {

                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al reiniciar la contraseña',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                        //enable button
                        document.getElementById('revertButton').disabled = false;
                        document.getElementById('cancelRevertButton').disabled = false;

                    }).finally(() => {
                        //limpiar
                        this.cleanForm();
                        //Recargar usuarios
                        this.getAllUsers();
                    });

                },
                async getAllUsers() {
                    let response = await fetch('/allUsers');
                    let data = await response.json();
                    this.usuarios = data;
                    this.searchUsuarios = data;

                },
                formatDate(date) {

                    let fecha = new Date(date);
                    let options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return fecha.toLocaleDateString('es-ES', options);

                },
                searchFn() {
                    let search = this.search.toLowerCase();
                    let users = this.searchUsuarios;

                    try {
                        this.filtered = users.filter(user => {
                            return user.nombre.toLowerCase().includes(search) ||
                                user.apellido.toLowerCase().includes(search) ||
                                user.usuario.toLowerCase().includes(search)
                        });
                    } catch (error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar el usuario',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }


                    this.usuarios = this.filtered;
                },
                async getAllEstados() {
                    let response = await fetch('/allEstados');
                    let data = await response.json();
                    this.estados = data;
                }
            },
            mounted() {
                this.getAllEstados();
                this.getAllUsers();
            }
        });
    </script>
@endsection
