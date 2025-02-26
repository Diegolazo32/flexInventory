@extends('Layouts.Navigation')

@section('title', 'Permisos')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Permisos</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal"
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
                        </button>-->
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
                                <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllPermisos"><i class="fa-solid fa-magnifying-glass"></i></button>
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
                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="permisos.error" class="alert alert-danger" role="alert">
                        <h3>@{{ permisos.error }}</h3>
                    </div>

                    <div v-if="permisos.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Grupo</th>

                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='permiso in permisos' :key="permiso.id">
                                    <td>
                                        @{{ permiso.nombre }}
                                    </td>

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
                                        <button id="editBTN" class="btn btn-primary" @click="editPermiso(permiso)">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                    </td>-->
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
        <div class="modal fade" id="crearPermisoModal" tabindex="-1" aria-labelledby="crearPermisoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
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
                                        <div class="invalid-tooltip" v-if="errors.nombre">@{{ errors.nombre }}</div>
                                    </div>
                                </div>
                                <!--descripcion-->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion"
                                            value="{{ old('descripcion') }}">
                                        <label for="floatingInput">Descripcion*</label>
                                        <div class="invalid-tooltip" v-if="errors.descripcion">@{{ errors.descripcion }}
                                        </div>
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
                                        <div class="invalid-tooltip" v-if="errors.grupo">@{{ errors.grupo }}</div>
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
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
                                        <div class="invalid-tooltip" v-if="editErrors.nombre">@{{ editErrors.nombre }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Descripcion -->
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcionEdit"
                                            name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                            v-model="editItem.descripcion">
                                        <label for="floatingInput">Descripcion*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.descripcion">@{{ editErrors.descripcion }}
                                        </div>
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
                                        <div class="invalid-tooltip" v-if="editErrors.grupo">
                                            @{{ editErrors.grupo }}</small>
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
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

                    } else {
                        swal.fire({
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

                    } else {
                        swal.fire({
                            title: 'Error',
                            text: 'Por favor, corrija los errores en el formulario.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar',
                        });
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
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombre').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.ruta) {
                        this.errors.ruta = 'Este campo es obligatorio';
                        document.getElementById('ruta').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('ruta').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.descripcion) {
                        this.errors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('descripcion').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.grupo) {
                        this.errors.grupo = 'Este campo es obligatorio';
                        document.getElementById('grupo').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('grupo').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.endpoint) {
                        this.errors.endpoint = 'Este campo es obligatorio';
                        document.getElementById('endpoint').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('endpoint').setAttribute('class', 'form-control is-valid');
                    }
                    if (!this.item.metodo) {
                        this.errors.metodo = 'Este campo es obligatorio';
                        document.getElementById('metodo').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('metodo').setAttribute('class', 'form-control is-valid');
                    }

                    this.validatePermisoname();
                },
                validateEditForm() {

                    this.editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.ruta) {
                        this.editErrors.ruta = 'Este campo es obligatorio';
                        document.getElementById('rutaEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('rutaEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.grupo) {
                        this.editErrors.grupo = 'Este campo es obligatorio';
                        document.getElementById('grupoEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('grupoEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.endpoint) {
                        this.editErrors.endpoint = 'Este campo es obligatorio';
                        document.getElementById('endpointEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('endpointEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.metodo) {
                        this.editErrors.metodo = 'Este campo es obligatorio';
                        document.getElementById('metodoEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('metodoEdit').setAttribute('class', 'form-control is-valid');
                    }



                    this.validateEditPermisoname();
                },
                validatePermisoname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9-_]{3,}$/;

                    if (!regex.test(this.item.nombre)) {
                        document.getElementById('nombre').setAttribute('class', 'form-control');
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
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
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
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

                    document.getElementById('nombre').setAttribute('class', 'form-control');
                    document.getElementById('ruta').setAttribute('class', 'form-control');
                    document.getElementById('descripcion').setAttribute('class', 'form-control');

                    document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                    document.getElementById('rutaEdit').setAttribute('class', 'form-control');
                    document.getElementById('descripcionEdit').setAttribute('class', 'form-control');

                    this.permisos = this.searchPermisos;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.getAllPermisos();
                },
                //Obtener recursos
                async getAllPermisos() {

                    try {
                        axios({
                            method: 'get',
                            url: '/allPermisos',
                            params: {
                                page: this.page,
                                per_page: this.per_page,
                                search: this.search,
                            }
                        }).then(response => {

                            this.loading = false;
                            this.permisos = response.data.data;
                            this.searchPermisos = response.data.data;

                            this.total = response.data.total;
                            this.totalPages = response.data.last_page;

                            if (this.page > this.totalPages) {
                                this.page = 1;
                                this.getAllPermisos();
                            } else {
                                this.page = response.data.current_page;
                            }

                            this.per_page = response.data.per_page;
                            this.nextPageUrl = response.data.next_page_url;
                            this.prevPageUrl = response.data.prev_page_url;

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
