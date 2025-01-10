@extends('layouts.Navigation')

@section('title', 'Permisos')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Permisos</h1>
                        <small class="text-muted">@{{ mensaje }}</small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crearPermisoModal" style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editPermisoModalBtn"
                            data-bs-target="#editPermisoModal" style="height: 40px;" hidden>
                            Editar permiso
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deletePermisoModalBtn"
                            data-bs-target="#deletePermisoModal" style="height: 40px;" hidden>
                            Eliminar permiso
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
                                <input type="text" class="form-control" name="search"
                                    placeholder="Buscar por nombre, descripcion o grupo" v-model="search">
                                <small class="text-danger" v-if="searchError">@{{ searchError }}</small>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;" @click="searchFn"><i
                                        class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabla de permisos -->
            <div class="row">
                <div class="card-body">
                    <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                    </div>

                    <div v-if="permisos.error" class="alert alert-danger" role="alert">
                        <h3>@{{ permisos.error }}</h3>
                    </div>

                    <div v-if="permisos.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <!--<th scope="col">ID</th>-->
                                    <th scope="col">Nombre</th>
                                    <!--<th scope="col">Ruta</th>-->
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Grupo</th>
                                    <!--<th scope="col">Endpoint</th>-->
                                    <!--<th scope="col">Metodo</th>-->
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='permiso in permisos' :key="permiso.id">
                                    <!--<td>@{{ permiso.id }}</td>-->
                                    <td>
                                        @{{ permiso.nombre }}
                                    </td>
                                    <!--<td v-if="permiso.ruta.length > 25">
                                            @{{ permiso.ruta.substring(0, 25) }}...
                                        </td>
                                        <td v-else>
                                            @{{ permiso.ruta }}
                                        </td>-->
                                    <td v-if="permiso.descripcion.length > 100">
                                        @{{ permiso.descripcion.substring(0, 100) }}...
                                    </td>
                                    <td v-else>
                                        @{{ permiso.descripcion }}
                                    </td>
                                    <td>
                                        @{{ grupos.find(grupo => grupo.id == permiso.grupo).descripcion }}
                                    </td>
                                    <!--<td>
                                            @{{ permiso.endpoint }}
                                        </td>
                                        <td>
                                            @{{ metodos.find(metodo => metodo.id == permiso.metodo).descripcion }}
                                        </td>-->
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editPermiso(permiso)">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <!--<button class="btn btn-danger" id="dltBTN" @click="DeletePermiso(permiso)">
                                                <i class="fas fa-trash"></i>
                                            </button>-->
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        <div class="d-flex justify-content-center" style="gap: 10px;">
                                            <ul class="pagination justify-content-center">
                                                <li class="page-item" :disabled="page === 1">
                                                    <a class="page-link" href="#" aria-label="Previous"
                                                        @click="pageMinus">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li class="page-item" v-for="pageNumber in totalPages"
                                                    :key="pageNumber" :class="{ active: pageNumber === page }">
                                                    <a class="page-link" href="#" @click="specificPage(pageNumber)">
                                                        @{{ pageNumber }}
                                                    </a>
                                                </li>
                                                <li class="page-item" :disabled="page === totalPages">
                                                    <a class="page-link" href="#" aria-label="Next"
                                                        @click="pagePlus">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul class="pagination justify-content-center">

                                                <li class="page-item">
                                                    <select class="form-select" v-model="per_page"
                                                        @change="changePerPage">
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
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="crearPermisoModal" tabindex="-1" aria-labelledby="crearPermisoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down mdl">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearPermisoModalLabel">Crear permiso </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('permisos.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!--Nombre-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" v-model="item.nombre"
                                            value="{{ old('nombre') }}">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger" v-if="errors.nombre">@{{ errors.nombre }}</small>
                                    </div>
                                </div>
                                <!--descripcion-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion"
                                            value="{{ old('descripcion') }}">
                                        <label for="floatingInput">Descripcion*</label>
                                        <small class="text-danger"
                                            v-if="errors.descripcion">@{{ errors.descripcion }}</small>
                                    </div>
                                </div>
                                <!-- Grupo -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="grupo" name="grupo" v-model="item.grupo"
                                            @blur="validateForm" @change="validateForm">
                                            <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">
                                                @{{ grupo.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Grupo*</label>
                                        <small class="text-danger" v-if="errors.grupo">@{{ errors.grupo }}</small>
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
        <div class="modal fade" id="editPermisoModal" tabindex="-1" aria-labelledby="editPermisoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down mdl">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editPermisoModalLabel">Editar permiso</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <!--Nombre-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.nombre">@{{ editErrors.nombre }}</small>
                                    </div>
                                </div>
                                <!-- Descripcion -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcionEdit"
                                            name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                            v-model="editItem.descripcion">
                                        <label for="floatingInput">Descripcion*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.descripcion">@{{ editErrors.descripcion }}</small>
                                    </div>
                                </div>
                                <!-- Grupo -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="grupoEdit" name="grupo"
                                            v-model="editItem.grupo" @blur="validateEditForm" @change="validateEditForm">
                                            <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">
                                                @{{ grupo.descripcion }}
                                            </option>
                                        </select>
                                        <label for="floatingInput">Grupo*</label>
                                        <small class="text-danger" v-if="editErrors.grupo">@{{ editErrors.grupo }}</small>
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
        <div class="modal fade" id="deletePermisoModal" tabindex="-1" aria-labelledby="deletePermisoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down mdl">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deletePermisoModalLabel">Eliminar permiso</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este permiso?</small>
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
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                item: {
                    nombre: '',
                    ruta: '',
                    descripcion: '',
                    endpoint: '',
                    grupo: '',
                    metodo: ''

                },
                editItem: {
                    id: '',
                    nombre: '',
                    ruta: '',
                    descripcion: '',
                    endpoint: '',
                    grupo: '',
                    metodo: ''
                },
                deleteItem: {
                    id: '',
                    nombre: '',
                    ruta: '',
                    descripcion: '',
                    endpoint: '',
                    grupo: '',
                    metodo: ''
                },
                showItem: {
                    id: '',
                    nombre: '',
                    ruta: '',
                    descripcion: '',
                    endpoint: '',
                    grupo: '',
                    metodo: ''
                },
                search: '',
                errors: {},
                editErrors: {},
                permisos: [],
                searchPermisos: [],
                filtered: [],
                estados: [],
                metodos: [{
                        id: 1,
                        descripcion: 'GET'
                    },
                    {
                        id: 2,
                        descripcion: 'POST'
                    },
                    {
                        id: 3,
                        descripcion: 'PUT'
                    },
                    {
                        id: 4,
                        descripcion: 'DELETE'
                    },
                ],
                grupos: [],
                loading: true,
                searchError: '',
                page: 1,
                per_page: 5,
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
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButton').disabled = true;

                        axios({
                            method: 'post',
                            url: '/permisos/store',
                            data: this.item
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                '<i class="fas fa-save"></i>';

                            //Cerrar modal
                            //document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Permiso creado',
                                    text: response.data.success,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
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
                                title: 'Error',
                                text: 'Ha ocurrido un error al crear la permiso',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar permisos
                            this.getAllPermisos();
                        })

                    }
                },
                //Editar
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
                            url: '/permisos/edit/' + this.editItem.id,
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
                                    title: 'Permiso editado',
                                    text: response.data.success,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.data.error,
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }


                        }).catch(error => {

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al editar la permiso',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar permisos
                            this.getAllPermisos();

                        })

                    }
                },
                editPermiso(permiso) {
                    this.editItem.nombre = permiso.nombre;
                    this.editItem.ruta = permiso.ruta;
                    this.editItem.descripcion = permiso.descripcion;
                    this.editItem.id = permiso.id;
                    this.editItem.grupo = permiso.grupo;
                    this.editItem.endpoint = permiso.endpoint;
                    this.editItem.metodo = permiso.metodo;

                    //dar click al boton de modal
                    document.getElementById('editPermisoModalBtn').click();

                },
                //Eliminar
                sendDeleteForm() {
                    //Inhabilitar botones
                    document.getElementById('deleteButton').disabled = true;
                    document.getElementById('canceldeleteButton').disabled = true;

                    //Cambiar icono de boton
                    document.getElementById('deleteButton').innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> Eliminando...';

                    axios({
                        method: 'delete',
                        url: '/permisos/delete/' + this.deleteItem.id,
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
                                title: 'Permiso eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        }

                    }).catch(error => {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al eliminar la permiso',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar permisos
                        this.getAllPermisos();
                    })


                },
                DeletePermiso(permiso) {
                    this.deleteItem.nombre = permiso.nombre;
                    this.deleteItem.ruta = permiso.ruta;
                    this.deleteItem.descripcion = permiso.descripcion;
                    this.deleteItem.grupo = permiso.grupo;
                    this.deleteItem.endpoint = permiso.endpoint;
                    this.deleteItem.metodo = permiso.metodo;
                    this.deleteItem.id = permiso.id;


                    //dar click al boton de modal
                    document.getElementById('deletePermisoModalBtn').click();
                },
                //Validaciones
                validateForm() {
                    this.errors = {};


                    if (!this.item.nombre) {
                        this.errors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombre').style.border = '1px solid red';
                    } else {
                        document.getElementById('nombre').style.border = '1px solid green';
                    }

                    if (!this.item.ruta) {
                        this.errors.ruta = 'Este campo es obligatorio';
                        document.getElementById('ruta').style.border = '1px solid red';
                    } else {
                        document.getElementById('ruta').style.border = '1px solid green';
                    }

                    if (!this.item.descripcion) {
                        this.errors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcion').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcion').style.border = '1px solid green';
                    }
                    if (!this.item.grupo) {
                        this.errors.grupo = 'Este campo es obligatorio';
                        document.getElementById('grupo').style.border = '1px solid red';
                    } else {
                        document.getElementById('grupo').style.border = '1px solid green';
                    }
                    if (!this.item.endpoint) {
                        this.errors.endpoint = 'Este campo es obligatorio';
                        document.getElementById('endpoint').style.border = '1px solid red';
                    } else {
                        document.getElementById('endpoint').style.border = '1px solid green';
                    }
                    if (!this.item.metodo) {
                        this.errors.metodo = 'Este campo es obligatorio';
                        document.getElementById('metodo').style.border = '1px solid red';
                    } else {
                        document.getElementById('metodo').style.border = '1px solid green';
                    }

                    this.validatePermisoname();
                },
                validateEditForm() {

                    editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('nombreEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.ruta) {
                        this.editErrors.ruta = 'Este campo es obligatorio';
                        document.getElementById('rutaEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('rutaEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcionEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.grupo) {
                        this.editErrors.grupo = 'Este campo es obligatorio';
                        document.getElementById('grupoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('grupoEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.endpoint) {
                        this.editErrors.endpoint = 'Este campo es obligatorio';
                        document.getElementById('endpointEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('endpointEdit').style.border = '1px solid green';
                    }
                    if (!this.editItem.metodo) {
                        this.editErrors.metodo = 'Este campo es obligatorio';
                        document.getElementById('metodoEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('metodoEdit').style.border = '1px solid green';
                    }



                    this.validateEditPermisoname();
                },
                validatePermisoname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9-_]{3,}$/;

                    if (!regex.test(this.item.nombre)) {
                        document.getElementById('nombre').style.border = '1px solid #ced4da';
                        document.getElementById('nombre').style.border = '1px solid red';
                        this.errors.nombre =
                            'El permiso debe tener al menos 3 caracteres y no contener espacios o caracteres especiales';
                    }

                    for (let i = 0; i < this.permisos.length; i++) {
                        if (this.permisos[i].nombre == this.item.nombre) {
                            this.errors.nombre = 'El permiso ya existe';
                        }
                    }
                },
                validateEditPermisoname() {

                    this.editErrors = {};

                    let regex = /^[a-zA-Z0-9-_]{3,}$/;

                    if (!regex.test(this.editItem.nombre)) {
                        document.getElementById('nombreEdit').style.border = '1px solid #ced4da';
                        document.getElementById('nombreEdit').style.border = '1px solid red';
                        this.editErrors.nombre =
                            'El permiso debe tener al menos 3 caracteres y no contener espacios o caracteres especiales';
                    }

                    //Eliminar del array la permiso que se esta editando
                    this.permisos = this.permisos.filter(permiso => permiso.id != this.editItem.id);

                    //recorrer this.permisos
                    for (let i = 0; i < this.permisos.length; i++) {
                        if (this.permisos[i].nombre == this.editItem.nombre) {
                            this.editErrors.nombre = 'El permiso ya existe';
                        }
                    }

                },
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllPermisos();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllPermisos();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllPermisos();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllPermisos();
                },
                //Limpiar formulario y busqueda
                searchFn() {

                    this.searchError = '';

                    if (this.search == null) {
                        this.productos = this.searchProductos;
                        this.searchError = 'El campo está vacío';
                        return;
                    }

                    if (!this.search) {
                        this.productos = this.searchProductos;
                        this.searchError = 'El campo está vacío';
                        return;
                    }

                    let search = this.search.toLowerCase();
                    let permisos = this.searchPermisos;

                    try {
                        this.filtered = permisos.filter(permiso => {
                            return permiso.descripcion.toLowerCase().includes(search) ||
                                permiso.nombre.toLowerCase().includes(search) ||
                                permiso.ruta.toLowerCase().includes(search) ||
                                permiso.endpoint.toLowerCase().includes(search) ||
                                this.grupos.find(grupo => grupo.id == permiso.grupo).descripcion
                                .toLowerCase().includes(search) ||
                                this.metodos.find(metodo => metodo.id == permiso.metodo).descripcion
                                .toLowerCase().includes(search);
                        });
                    } catch (error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar la permiso',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }

                    if (this.filtered.length == 0) {
                        this.searchError = 'No se encontraron resultados';
                    }

                    this.permisos = this.filtered;
                },
                cleanForm() {

                    this.item = {
                        nombre: '',
                        ruta: '',
                        descripcion: '',
                    };

                    this.errors = {};
                    this.editErrors = {};
                    this.search = '';
                    //this.permisos = [];
                    this.editItem = {
                        id: '',
                        nombre: '',
                        ruta: '',
                        descripcion: '',
                    };
                    this.deleteItem = {
                        id: '',
                        nombre: '',
                        ruta: '',
                        descripcion: '',
                    };

                    document.getElementById('nombre').style.border = '1px solid #ced4da';
                    document.getElementById('ruta').style.border = '1px solid #ced4da';
                    document.getElementById('descripcion').style.border = '1px solid #ced4da';

                    document.getElementById('nombreEdit').style.border = '1px solid #ced4da';
                    document.getElementById('rutaEdit').style.border = '1px solid #ced4da';
                    document.getElementById('descripcionEdit').style.border = '1px solid #ced4da';

                    this.permisos = this.searchPermisos;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.permisos = this.searchPermisos;
                },
                //Obtener recursos
                async getAllPermisos() {
                    try {
                        axios({
                            method: 'get',
                            url: '/allPermisosP',
                            params: {
                                page: this.page,
                                per_page: this.per_page
                            }
                        }).then(response => {
                            this.loading = false;
                            this.permisos = response.data.data;
                            this.searchPermisos = response.data.data;

                            this.total = response.data.total;
                            this.totalPages = response.data.last_page;
                            this.page = response.data.current_page;
                            this.per_page = response.data.per_page;
                            this.nextPageUrl = response.data.next_page_url;
                            this.prevPageUrl = response.data.prev_page_url;

                            console.log(response.data);
                        }).catch(error => {
                            this.loading = false;
                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al obtener los permisos',
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

                        //console.log(this.estados);

                    } catch (error) {

                    }


                },
                async getAllGrupos() {
                    let response = await fetch('/allGrupos');
                    let data = await response.json();
                    this.grupos = data;
                }

            },
            mounted() {
                this.getAllEstados();
                this.getAllGrupos();
                this.getAllPermisos();

            }
        });
    </script>
@endsection
