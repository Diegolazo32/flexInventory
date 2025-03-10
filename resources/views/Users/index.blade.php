@extends('Layouts.Navigation')

@section('title', 'Usuarios')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Usuarios</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
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
                    <div class="col-lg-10">

                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="search" @keyup.enter="getAllUsers"
                                    placeholder="Buscar por nombre, apellido, usuario o DUI" v-model="search">
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllUsers"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de usuarios -->
            <div class="row">
                <div class="card-body">
                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="usuarios.error" class="alert alert-danger" role="alert">
                        <h3>@{{ usuarios.error }}</h3>
                    </div>

                    <div v-if="usuarios.length > 0" class="table-responsive">

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
                                        @{{ formatDate(usuario.fechaNacimiento) ?? '-' }}
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
                                        <span class="badge bg-primary">@{{ roles.find(rol => usuario.rol == rol.id).descripcion }}</span>
                                    </td>

                                    <td v-else>
                                        <span class="badge bg-secondary">@{{ roles.find(rol => usuario.rol == rol.id).descripcion }}</span>
                                    </td>
                                    <td v-if="usuario.estado == 1">
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-success">
                                            @{{ estados.find(estado => estado.id == usuario.estado).descripcion }}
                                        </span>
                                    </td>
                                    <td v-else-if="usuario.estado == 8">
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-warning">
                                            @{{ estados.find(estado => estado.id == usuario.estado).descripcion }}
                                        </span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span class="badge bg-danger" v-else>@{{ estados.find(estado => estado.id == usuario.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editUser(usuario)"
                                            :disabled="loading">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-warning" v-if="usuario.hasPassword == true" id="revertBTN"
                                            :disabled="loading" @click="openRevertModal(usuario)">
                                            <i class="fas fa-lock"></i>
                                        </button>

                                        <!-- Id del boton asociado al usuario -->
                                        <button class="btn btn-warning" v-else disabled id="revertBTN">
                                            <i class="fas fa-lock"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteUser(usuario)"
                                            :disabled="loading">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center" style="gap: 10px;">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :disabled="page === 1">
                            <a class="page-link" href="#" aria-label="Previous" @click="pageMinus">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item" v-for="pageNumber in totalPages" :key="pageNumber"
                            :class="{ active: pageNumber === page }">
                            <a class="page-link" href="#" @click="specificPage(pageNumber)">
                                @{{ pageNumber }}
                            </a>
                        </li>
                        <li class="page-item" :disabled="page === totalPages">
                            <a class="page-link" href="#" aria-label="Next" @click="pagePlus">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="pagination justify-content-center">

                        <li class="page-item">
                            <select class="form-select" v-model="per_page" @change="changePerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade " id="crearUserModal" tabindex="-1" aria-labelledby="crearUserModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearUserModalLabel">Crear usuario </h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('users.store') }}" method="POST"
                            @submit.prevent="sendForm">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            maxlength="50" placeholder="Nombre" @blur="validateForm"
                                            @keyup="validateForm" v-model="item.nombre">
                                        <label for="floatingInput">Nombre<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.nombre">@{{ errors.nombre }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="apellido" name="apellido"
                                            maxlength="50" placeholder="Apellido" v-model="item.apellido"
                                            @blur="validateForm" @keyup="validateForm">
                                        <label for="floatingInput">Apellido<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.apellido">@{{ errors.apellido }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="DUI" name="DUI"
                                            placeholder="DUI" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.DUI" maxlength="10"> <label for="floatingInput">DUI<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.DUI">@{{ errors.DUI }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="fechaNacimiento"
                                            name="fechaNacimiento" placeholder="Fecha de nacimiento"
                                            @change="validateDate" @blur="validateDate" v-model="item.fechaNacimiento">
                                        <label for="floatingInput">Fecha de nacimiento<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.fechaNacimiento">@{{ errors.fechaNacimiento }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="genero" name="genero" v-model="item.genero"
                                            @change="validateForm">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                        <label for="floatingInput">Genero<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.genero">@{{ errors.genero }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="usuario" name="usuario"
                                            min="5" maxlength="50" placeholder="Usuario" v-model="item.usuario"
                                            @blur="validateForm" @keyup="validateForm">
                                        <label for="floatingInput">Usuario<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.usuario">@{{ errors.usuario }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="rol" name="rol" v-model="item.rol"
                                            @change="validateForm">
                                            <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                                                @{{ rol.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Rol<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.rol">@{{ errors.rol }}</div>
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
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editUserModalLabel">Editar usuario</h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit" @submit.prevent="sendFormEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">

                                    <div class="form-floating mb-3">
                                        <!-- Nombre -->
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            maxlength="50" placeholder="Nombre" @blur="validateEditForm"
                                            @keyup="validateEditForm" v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.nombre">@{{ editErrors.nombre }}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Apellido -->
                                        <input type="text" class="form-control" id="apellidoEdit" name="apellido"
                                            maxlength="50" placeholder="Apellido" v-model="editItem.apellido"
                                            @blur="validateEditForm" @keyup="validateEditForm">
                                        <label for="floatingInput">Apellido<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.apellido">@{{ editErrors.apellido }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- DUI -->
                                        <input type="text" class="form-control" id="DUIEdit" name="DUI"
                                            placeholder="DUI" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.DUI" maxlength="10">
                                        <label for="floatingInput">DUI<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.DUI">@{{ editErrors.DUI }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Fecha de nacimiento -->
                                        <input type="date" class="form-control" id="fechaNacimientoEdit"
                                            name="fechaNacimiento" placeholder="Fecha de nacimiento" @blur="validateDate"
                                            @change="validateDate" v-model="editItem.fechaNacimiento">
                                        <label for="floatingInput">Fecha de nacimiento<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.fechaNacimiento">
                                            @{{ editErrors.fechaNacimiento }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Genero -->
                                        <select class="form-select" id="generoEdit" name="genero"
                                            v-model="editItem.genero" @change="validateEditForm">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                        <label for="floatingInput">Genero<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.genero">@{{ editErrors.genero }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Usuario -->
                                        <input type="text" class="form-control" id="usuarioEdit" name="usuario"
                                            maxlength="50" placeholder="Usuario" v-model="editItem.usuario"
                                            @blur="validateEditForm" @keyup="validateEditForm"
                                            @keyup.enter="sendFormEdit">
                                        <label for="floatingInput">Usuario<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.usuario">@{{ editErrors.usuario }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Rol -->
                                        <select class="form-select" id="rolEdit" name="rol" v-model="editItem.rol"
                                            @change="validateEditForm">
                                            <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                                                @{{ rol.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Rol<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.rol">@{{ editErrors.rol }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Estado -->
                                        <select class="form-select" id="estadoEdit" name="estado"
                                            :disabled="estados.error" v-model="editItem.estado" @blur="validateEditForm"
                                            @change="validateEditForm">
                                            <option v-for="estado in estados" :key="estado.id"
                                                :value="estado.id">
                                                @{{ estado.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Estado<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.estado">@{{ editErrors.estado }}
                                        </div>
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
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
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
        <div class="modal fade" id="revertModal" tabindex="-1" aria-labelledby="revertModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
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
                searchError: '',
                loading: true,
                page: 1,
                per_page: 10,
                total: 0,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
                mensaje: '',
                posiblesMensajes: ['Gestion de usuarios', 'Administracion de usuarios', 'Personas de confianza'],
                roles: [],
            },
            methods: {

                //Funcion para obtener recursos
                async getAllUsers() {

                    this.loading = true;
                    this.errors = {};
                    this.editErrors = {};

                    try {
                        axios({
                            method: 'get',
                            url: '/allUsers',
                            params: {
                                page: this.page,
                                per_page: this.per_page,
                                search: this.search
                            }
                        }).then(response => {
                            this.loading = false;
                            this.usuarios = response.data.data;
                            this.searchUsuarios = response.data.data;

                            this.total = response.data.total;
                            this.totalPages = response.data.last_page;
                            if (this.page > this.totalPages) {
                                this.page = 1;
                                this.getAllUsers();
                            } else {
                                this.page = response.data.current_page;
                            }
                            this.per_page = response.data.per_page;
                            this.nextPageUrl = response.data.next_page_url;
                            this.prevPageUrl = response.data.prev_page_url;


                        }).catch(error => {
                            this.loading = false;
                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener los usuarios',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {

                    }

                },
                async getAllEstados() {

                    try {

                        let response = await fetch('/allEstados');
                        let data = await response.json();
                        this.estados = data;

                    } catch (error) {

                    }


                },
                async getAllRoles() {

                    try {

                        let response = await fetch('/allRoles');
                        let data = await response.json();
                        this.roles = data;

                    } catch (error) {

                    }

                },
                //Funciones de asignacion
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
                openRevertModal(user) {
                    this.revertItem = user;
                    document.getElementById('revertModalBtn').click();
                },
                //Funciones de validacion
                validateForm() {
                    this.errors = {};
                    if (!this.item.nombre) {
                        this.errors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombre').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.genero) {
                        this.errors.genero = 'Este campo es obligatorio';
                        document.getElementById('genero').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('genero').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.usuario) {
                        this.errors.usuario = 'Este campo es obligatorio';
                        document.getElementById('usuario').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('usuario').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.rol) {
                        this.errors.rol = 'Este campo es obligatorio';
                        document.getElementById('rol').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('rol').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.apellido) {
                        this.errors.apellido = 'Este campo es obligatorio';
                        document.getElementById('apellido').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('apellido').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.DUI) {
                        this.errors.DUI = 'Este campo es obligatorio';
                        document.getElementById('DUI').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('DUI').setAttribute('class', 'form-control is-valid');
                    }

                    this.validateDate();
                    this.validateUsername();
                },
                validateEditForm() {

                    this.editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.genero) {
                        this.editErrors.genero = 'Este campo es obligatorio';
                        document.getElementById('generoEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('generoEdit').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.editItem.usuario) {
                        this.editErrors.usuario = 'Este campo es obligatorio';
                        document.getElementById('usuarioEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('usuarioEdit').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.editItem.rol) {
                        this.editErrors.rol = 'Este campo es obligatorio';
                        document.getElementById('rolEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('rolEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.apellido) {
                        this.editErrors.apellido = 'Este campo es obligatorio';
                        document.getElementById('apellidoEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('apellidoEdit').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.editItem.DUI) {
                        this.editErrors.DUI = 'Este campo es obligatorio';
                        document.getElementById('DUIEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('DUIEdit').setAttribute('class', 'form-control is-valid');
                    }

                    //Si el estado es activo se le asigna borde verde al campo
                    if (this.editItem.estado == 1) {
                        document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');
                    } else {
                        document.getElementById('estadoEdit').setAttribute('class', 'form-control is-invalid');
                    }

                    this.validateEditDate();
                    this.validateEditUsername();


                },
                validateDate() {
                    let date = new Date(this.item.fechaNacimiento);
                    let today = new Date();

                    if (!this.item.fechaNacimiento) {
                        this.errors.fechaNacimiento = 'Este campo es obligatorio';
                        document.getElementById('fechaNacimiento').setAttribute('class', 'form-control is-invalid');
                        return;
                    }

                    if (date > today) {
                        document.getElementById('fechaNacimiento').setAttribute('class', 'form-control is-invalid');
                        this.errors.fechaNacimiento = 'La fecha de nacimiento no puede ser mayor a la fecha actual';
                    } else {
                        document.getElementById('fechaNacimientoEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                    if (today.getFullYear() - date.getFullYear() < 18) {
                        this.errors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        document.getElementById('fechaNacimiento').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('fechaNacimiento').setAttribute('class', 'form-control is-valid');
                    }
                },
                validateEditDate() {
                    let date = new Date(this.editItem.fechaNacimiento);
                    let today = new Date();

                    if (!this.editItem.fechaNacimiento) {
                        this.editErrors.fechaNacimiento = 'Este campo es obligatorio';
                        document.getElementById('fechaNacimientoEdit').setAttribute('class',
                            'form-control is-invalid');
                        return;
                    }

                    if (date > today) {
                        this.editErrors.fechaNacimiento =
                            'La fecha de nacimiento no puede ser mayor a la fecha actual';
                        document.getElementById('fechaNacimientoEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('fechaNacimientoEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                    if (today.getFullYear() - date.getFullYear() < 18) {
                        this.editErrors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        document.getElementById('fechaNacimientoEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('fechaNacimientoEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                },
                validateUsername() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9]{5,}$/;

                    if (!regex.test(this.item.usuario)) {

                        document.getElementById('usuario').setAttribute('class', 'form-control is-invalid');
                        this.errors.usuario =
                            'El usuario debe tener al menos 5 caracteres y no contener espacios o caracteres especiales';
                    }

                    for (let i = 0; i < this.usuarios.length; i++) {
                        if (this.usuarios[i].usuario == this.item.usuario) {
                            document.getElementById('usuario').setAttribute('class', 'form-control is-invalid');
                            this.errors.usuario = 'El usuario ya existe';
                        }
                    }
                },
                validateEditUsername() {

                    let regex = /^[a-zA-Z0-9]{5,}$/;

                    if (!regex.test(this.editItem.usuario)) {
                        document.getElementById('usuarioEdit').setAttribute('class', 'form-control is-invalid');
                        this.editErrors.usuario =
                            'El usuario debe tener al menos 5 caracteres y no contener espacios o caracteres especiales';
                    }

                    //Eliminar del array el usuario que se esta editando
                    this.usuarios = this.usuarios.filter(user => user.id != this.editItem.id);

                    //recorrer this.usuarios
                    for (let i = 0; i < this.usuarios.length; i++) {
                        if (this.usuarios[i].usuario == this.editItem.usuario) {
                            document.getElementById('usuarioEdit').setAttribute('class', 'form-control is-invalid');
                            this.editErrors.usuario = 'El usuario ya existe';
                        }
                    }

                },
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllUsers();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllUsers();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllUsers();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllUsers();
                },
                //Funciones de envio de formularios
                sendForm() {
                    this.validateForm();

                    if (Object.keys(this.errors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitForm').innerHTML =
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Guardando...';

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
                                'Guardar';

                            //Cerrar modal
                            document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Usuario creado',
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
                                text: error,
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
                sendFormEdit() {
                    this.validateEditForm();

                    if (Object.keys(this.editErrors).length === 0) {

                        //Cambiar icono de boton
                        document.getElementById('SubmitFormEdit').innerHTML =
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Guardando...';

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
                                    toast: true,
                                    showCloseButton: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    title: 'Usuario actualizado',
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
                                text: error,
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
                sendDeleteForm() {
                    //Inhabilitar botones
                    document.getElementById('deleteButton').disabled = true;
                    document.getElementById('canceldeleteButton').disabled = true;

                    //Cambiar icono de boton
                    document.getElementById('deleteButton').innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Eliminando...';

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
                        } else {


                            //Habilitar boton
                            document.getElementById('deleteButton').disabled = false;
                            document.getElementById('canceldeleteButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('deleteButton').innerHTML =
                                'Eliminar';

                            //Cerrar modal
                            document.getElementById('canceldeleteButton').click();

                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Usuario eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
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
                            text: error,
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
                revertPassword($user) {

                    //change icon to loading
                    document.getElementById('revertButton').innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
                    //disable button
                    document.getElementById('revertButton').disabled = true;
                    document.getElementById('cancelRevertButton').disabled = true;

                    axios({
                        url: '/users/rstpsw/' + $user.id,
                        method: 'POST',
                    }).then(response => {

                        //change icon to lock
                        document.getElementById('revertButton').innerHTML =
                            'Restablecer';

                        //enable button
                        document.getElementById('revertButton').disabled = false;
                        document.getElementById('cancelRevertButton').disabled = false;

                        //close modal
                        document.getElementById('cancelRevertButton').click();

                        if (response.data.success) {
                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Contraseña restablecida',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
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

                        //enable button
                        document.getElementById('revertButton').disabled = false;
                        document.getElementById('cancelRevertButton').disabled = false;

                        //Cerrar modal
                        document.getElementById('cancelRevertButton').click();

                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: error,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });


                    }).finally(() => {
                        //limpiar
                        this.cleanForm();
                        //Recargar usuarios
                        this.getAllUsers();
                    });

                },
                //Funciones de limpieza
                cleanForm() {
                    this.item = {
                        nombre: '',
                        apellido: '',
                        DUI: '',
                        fechaNacimiento: '',
                        genero: '',
                        usuario: '',
                        rol: '',
                    };
                    this.errors = {};
                    this.editErrors = {};
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

                    document.getElementById('nombre').setAttribute('class', 'form-control');
                    document.getElementById('apellido').setAttribute('class', 'form-control');
                    document.getElementById('DUI').setAttribute('class', 'form-control');
                    document.getElementById('fechaNacimiento').setAttribute('class', 'form-control');
                    document.getElementById('genero').setAttribute('class', 'form-control');
                    document.getElementById('usuario').setAttribute('class', 'form-control');
                    document.getElementById('rol').setAttribute('class', 'form-control');

                    document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                    document.getElementById('apellidoEdit').setAttribute('class', 'form-control');
                    document.getElementById('DUIEdit').setAttribute('class', 'form-control');
                    document.getElementById('fechaNacimientoEdit').setAttribute('class', 'form-control');
                    document.getElementById('generoEdit').setAttribute('class', 'form-control');
                    document.getElementById('usuarioEdit').setAttribute('class', 'form-control');
                    document.getElementById('rolEdit').setAttribute('class', 'form-control');
                    document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                    //this.getAllUsers();
                    this.usuarios = this.searchUsuarios;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.getAllUsers();
                },
                //Funciones de formateo
                formatDate(date) {

                    let dateObj = new Date(date);
                    let month = dateObj.getUTCMonth() + 1;
                    let day = dateObj.getUTCDate();
                    let year = dateObj.getUTCFullYear();

                    return day + "/" + month + "/" + year;

                },
                //Misc.
                randomMessage() {
                    let random = Math.floor(Math.random() * this.posiblesMensajes.length);
                    this.mensaje = this.posiblesMensajes[random];
                }

            },
            mounted() {
                this.getAllEstados();
                this.getAllRoles();
                this.randomMessage();
                this.getAllUsers();
            }
        });
    </script>
@endsection
