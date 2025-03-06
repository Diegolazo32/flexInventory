@extends('Layouts.Navigation')

@section('title', 'Roles')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Roles</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearRolModal"
                            style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editRolModalBtn"
                            data-bs-target="#editRolModal" style="height: 40px;" hidden>
                            Editar rol
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteRolModalBtn"
                            data-bs-target="#deleteRolModal" style="height: 40px;" hidden>
                            Eliminar rol
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="permisosModalBtn"
                            data-bs-target="#permisosModal" style="height: 40px;" hidden>
                            Editar permisos
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
                                <input type="text" class="form-control" name="search" @keyup.enter="getAllRoles"
                                    placeholder="Buscar por descripción" v-model="search">
                                <div class="invalid-tooltip"v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllRoles"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de roles -->
            <div class="row">
                <div class="card-body">

                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="roles.error" class="alert alert-danger" role="alert">
                        <h3>@{{ roles.error }}</h3>
                    </div>

                    <div v-if="roles.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='rol in roles' :key="rol.id">
                                    <td v-if="rol.descripcion.length > 15">
                                        @{{ rol.descripcion.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ rol.descripcion }}
                                    </td>
                                    <td v-if="rol.estado == 1">
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-success">
                                            @{{ estados.find(estado => estado.id == rol.estado).descripcion }}
                                        </span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span class="badge bg-danger" v-else>@{{ estados.find(estado => estado.id == rol.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editRol(rol)"
                                            :disabled="rol.estado == 2 || loading">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <button id="permisosBtn" class="btn btn-warning" @click="getPermisos(rol)"
                                            :disabled="rol.estado == 2 || loading">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteRol(rol)"
                                            :disabled="rol.estado == 2 || loading">
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
        <div class="modal fade" id="crearRolModal" tabindex="-1" aria-labelledby="crearRolModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearRolModalLabel">Crear rol </h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('roles.store') }}" method="POST"
                            @submit.prevent="sendForm">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            @keyup.enter="sendForm" placeholder="Descripcion" @blur="validateForm"
                                            v-model="item.descripcion">
                                        <label for="floatingInput">Descripcion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.descripcion">@{{ errors.descripcion }}
                                        </div>
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
        <div class="modal fade" id="editRolModal" tabindex="-1" aria-labelledby="editRolModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editRolModalLabel">Editar rol</h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit" @submit.prevent="sendFormEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">

                                    <div class="form-floating mb-3">
                                        <!-- Descripcion -->
                                        <input type="text" class="form-control" id="descripcionEdit"
                                            name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                            v-model="editItem.descripcion" @keyup.enter="sendFormEdit">
                                        <label for="floatingInput">Descripcion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.descripcion">@{{ editErrors.descripcion }}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Estado -->
                                        <select class="form-select" id="estadoEdit" name="estado"
                                            :disabled="estados.error" :disabled="estados.error" v-model="editItem.estado"
                                            @blur="validateEditForm" @change="validateEditForm">
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
        <div class="modal fade" id="deleteRolModal" tabindex="-1" aria-labelledby="deleteRolModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteRolModalLabel">Eliminar rol</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este rol?</small>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h3>Descripcion: @{{ deleteItem.descripcion }}</h3>
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

        <!--Permisos modal-->
        <div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="permisosModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h3 class="modal-title fs-5" id="permisosModalLabel">Permisos de: @{{ permisosItem.descripcion }}
                        </h3>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="table-responsive">
                                    <div class="card" v-for="grupo in grupos" :key="grupo.id"
                                        style="margin-bottom: 10px;">

                                        <div class="card-title" data-bs-toggle="collapse"
                                            :data-bs-target="'#collapse' + grupo.id" aria-expanded="false"
                                            style="display: flex; justify-content: space-between; padding-left: 15px;
                                        padding-right: 15px; padding-top: 10px; cursor: pointer;">
                                            <h4>@{{ grupo.descripcion }}</h4>

                                            <button class="btn btn-outline-secondary " type="button"
                                                data-bs-toggle="collapse" :data-bs-target="'#collapse' + grupo.id"
                                                aria-expanded="false">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>

                                        </div>
                                        <div class="collapse" :id="'collapse' + grupo.id">
                                            <hr>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-3" v-for='permiso in permisos'
                                                        :key="permiso.id" v-if="permiso.grupo == grupo.id"
                                                        style="margin-bottom:10px; display: flex; text-align: center; justify-content: flex-start; padding-left: 30px; ">
                                                        <div>
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="permiso.id"
                                                                :checked="permisosItem.permisos.includes(permiso.id)"
                                                                :value="permiso.id" @change="togglePermiso(permiso)"
                                                                style="margin-right: 10px;">
                                                            <label class="form-check-label" :for="permiso.id">
                                                                @{{ permiso.descripcion }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="cancelPermisosButton" @click="cleanForm">Cerrar</button>
                        <button type="button" class="btn btn-warning" id="SubmitPermisos"
                            @click="sendPermisosForm">Guardar</button>
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
                    descripcion: '',
                },
                editItem: {
                    id: '',
                    descripcion: '',
                    estado: '',
                },
                deleteItem: {
                    id: '',
                    descripcion: '',
                    estado: '',
                },
                permisosItem: {
                    id: '',
                    descripcion: '',
                    permisos: [],
                    estado: '',
                },
                //Permisos
                permisos: [],
                //Permisos del rol
                permisosRol: [],
                search: '',
                errors: {},
                editErrors: {},
                roles: [],
                searchRoles: [],
                filtered: [],
                estados: [],
                grupos: [],
                icon: 'fas fa-chevron-down',
                loading: true,
                searchError: '',
                page: 1,
                per_page: 10,
                total: 0,
                totalPages: 0,
                nextPageUrl: '',
                prevPageUrl: '',
            },
            methods: {
                //Crear
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
                            url: '/roles/store',
                            data: this.item
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

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
                                    title: 'Rol creado',
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

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                'Guardar';

                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Error',
                                text: 'Ha ocurrido un error al crear la rol',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //Cambiar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                'Guardar';

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //limpiar
                            this.cleanForm();
                            //Recargar roles
                            this.getAllRoles();
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
                //Editar
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
                            url: '/roles/edit/' + this.editItem.id,
                            data: this.editItem
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

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
                                    title: 'Rol actualizado',
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
                                text: 'Ha ocurrido un error al editar la rol',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //Quitar icono de boton
                            document.getElementById('SubmitFormEdit').innerHTML = 'Guardar';

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                            //limpiar
                            this.cleanForm();
                            //Recargar roles
                            this.getAllRoles();

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
                editRol(rol) {
                    this.editItem.descripcion = rol.descripcion;
                    this.editItem.estado = rol.estado;
                    this.editItem.id = rol.id;

                    //dar click al boton de modal
                    document.getElementById('editRolModalBtn').click();

                },
                //Eliminar
                sendDeleteForm() {
                    //Inhabilitar botones
                    document.getElementById('deleteButton').disabled = true;
                    document.getElementById('canceldeleteButton').disabled = true;

                    //Cambiar icono de boton
                    document.getElementById('deleteButton').innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Eliminando...';

                    axios({
                        method: 'delete',
                        url: '/roles/delete/' + this.deleteItem.id,
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
                                '<i class="fas fa-trash"></i>';

                            //Cerrar modal
                            document.getElementById('canceldeleteButton').click();

                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Rol eliminado',
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
                            text: 'Ha ocurrido un error al eliminar la rol',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar roles
                        this.getAllRoles();
                    })


                },
                DeleteRol(rol) {
                    this.deleteItem.descripcion = rol.descripcion;

                    this.deleteItem.id = rol.id;

                    //dar click al boton de modal
                    document.getElementById('deleteRolModalBtn').click();
                },
                //Permisos
                getPermisos(rol) {

                    this.getAllRolPermisos(rol);
                    this.permisosItem.descripcion = rol.descripcion;
                    this.permisosItem.id = rol.id;
                    this.permisosItem.estado = rol.estado;

                    //dar click al boton de modal
                    document.getElementById('permisosModalBtn').click();
                },
                sendPermisosForm() {

                    //Inhabilitar botones
                    document.getElementById('SubmitPermisos').disabled = true;
                    document.getElementById('cancelPermisosButton').disabled = true;

                    //Cambiar icono de boton
                    document.getElementById('SubmitPermisos').innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Guardando...';

                    axios({
                        method: 'post',
                        url: '/roles/permisos/' + this.permisosItem.id,
                        data: this.permisosItem
                    }).then(response => {

                        if (response.data.error) {
                            //Habilitar boton
                            document.getElementById('SubmitPermisos').disabled = false;
                            document.getElementById('cancelPermisosButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitPermisos').innerHTML = 'Guardar';

                            //Cerrar modal
                            document.getElementById('cancelPermisosButton').click();

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
                            document.getElementById('SubmitPermisos').disabled = false;
                            document.getElementById('cancelPermisosButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitPermisos').innerHTML = 'Guardar';

                            //Cerrar modal
                            document.getElementById('cancelPermisosButton').click();

                            swal.fire({
                                toast: true,
                                showCloseButton: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                title: 'Permisos actualizados',
                                text: response.data.success +
                                    ' aplicando cambios, espere un momento...',
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                            window.location.reload();
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
                            text: 'Ha ocurrido un error al actualizar los permisos',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //Habilitar boton
                        document.getElementById('SubmitPermisos').disabled = false;
                        document.getElementById('cancelPermisosButton').disabled = false;

                        //Cerrar modal
                        document.getElementById('cancelPermisosButton').click();

                        //Quitar icono de boton
                        document.getElementById('SubmitPermisos').innerHTML = 'Guardar';
                        //limpiar
                        this.cleanForm();
                        //Recargar roles
                        this.getAllRoles();
                    })


                },
                togglePermiso(permiso) {

                    let permisoId = permiso.id;

                    if (this.permisosItem.permisos.includes(permisoId)) {
                        this.permisosItem.permisos = this.permisosItem.permisos.filter(permiso => permiso !=
                            permisoId);
                    } else {
                        this.permisosItem.permisos.push(permisoId);
                    }

                },
                //Validaciones
                validateForm() {
                    this.errors = {};

                    if (!this.item.descripcion) {
                        this.errors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-valid');
                    }


                    this.validateRolname();
                },
                validateEditForm() {

                    this.editErrors = {};

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-valid');
                    }

                    document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');

                    this.validateEditRolname();
                },
                validateRolname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.item.descripcion)) {
                        document.getElementById('descripcion').setAttribute('class', 'form-control');
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-invalid');
                        this.errors.descripcion =
                            'El rol debe tener al menos 3 caracteres y no contener espacios o caracteres especiales';
                    }

                    for (let i = 0; i < this.roles.length; i++) {
                        if (this.roles[i].descripcion == this.item.descripcion) {
                            this.errors.descripcion = 'El rol ya existe';
                        }
                    }
                },
                validateEditRolname() {

                    this.editErrors = {};

                    let regex = /^[a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.editItem.descripcion)) {
                        document.getElementById('descripcionEdit').setAttribute('class', 'form-control');
                        document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-invalid');
                        this.editErrors.descripcion =
                            'El rol debe tener al menos 3 caracteres y no contener espacios o caracteres especiales';
                    }

                    //Eliminar del array la rol que se esta editando
                    this.roles = this.roles.filter(rol => rol.id != this.editItem.id);

                    //recorrer this.roles
                    for (let i = 0; i < this.roles.length; i++) {
                        if (this.roles[i].descripcion == this.editItem.descripcion) {
                            this.editErrors.descripcion = 'El rol ya existe';
                        }
                    }

                },
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllEstados();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllEstados();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllEstados();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllEstados();
                },
                //Limpiar formulario y busqueda
                cleanForm() {

                    this.item = {
                        descripcion: '',
                    };

                    this.errors = {};
                    this.editErrors = {};
                    this.search = '';
                    //this.roles = [];
                    this.editItem = {
                        id: '',
                        descripcion: '',
                        estado: ''
                    };
                    this.deleteItem = {
                        id: '',
                        descripcion: '',
                        estado: ''
                    };
                    this.permisosItem = {
                        id: '',
                        descripcion: '',
                        permisos: [],
                        estado: '',
                    };

                    document.getElementById('descripcion').setAttribute('class', 'form-control');

                    document.getElementById('descripcionEdit').setAttribute('class', 'form-control');
                    document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                    this.roles = this.searchRoles;
                },
                cleanSearch() {
                    this.search = '';
                    this.getAllRoles();
                },
                //Obtener recursos
                async getAllRoles() {

                    this.loading = true;
                    this.errors = {};
                    this.editErrors = {};

                    try {
                        axios({
                            method: 'get',
                            url: '/allRoles',
                            params: {
                                page: this.page,
                                per_page: this.per_page,
                                search: this.search
                            }
                        }).then(response => {
                            this.loading = false;
                            this.roles = response.data.data;
                            this.searchRoles = response.data.data;

                            this.total = response.data.total;
                            this.totalPages = response.data.last_page;
                            if (this.page > this.totalPages) {
                                this.page = 1;
                                this.getAllRoles();
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
                                text: 'Ha ocurrido un error al obtener los roles',
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
                async getAllPermisos() {
                    let response = await fetch('/allPermisos');
                    let data = await response.json();
                    this.permisos = data;
                },
                async getAllRolPermisos(rol) {
                    let response = await fetch('/permisosByRol/' + rol.id);
                    let data = await response.json();
                    this.permisosRol = data;

                    this.permisosRol.forEach(permiso => {
                        this.permisosItem.permisos.push(permiso.permiso);
                    });
                },
                async getAllGrupos() {
                    let response = await fetch('/allGrupos');
                    let data = await response.json();
                    this.grupos = data;
                },
            },
            mounted() {
                this.getAllEstados();
                this.getAllGrupos();
                this.getAllPermisos();
                this.getAllRoles();
            }
        });
    </script>
@endsection
