@extends('layouts.Navigation')

@section('title', 'Unidades')

@section('content')
<div id="app">
    <div class="card hoverCard">
        <div class="card-header">
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-lg-10">
                    <h1>Unidades</h1>
                    <small class="text-muted"></small>
                </div>
                <!-- Botones de accion -->
                <div class="col-lg-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#crearUnidadModal" style="height: 40px;">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editUnidadModalBtn"
                        data-bs-target="#editUnidadModal" style="height: 40px;" hidden>
                        Editar unidad
                    </button>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteUnidadModalBtn"
                        data-bs-target="#deleteUnidadModal" style="height: 40px;" hidden>
                        Eliminar unidad
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
                                placeholder="Buscar por descripcion o abreviatura" v-model="search">
                            <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                        </div>
                        <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                            <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                @click="getAllUnidades"><i class="fa-solid fa-magnifying-glass"></i></button>
                            <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla de unidades -->
        <div class="row">
            <div class="card-body">

                <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                </div>

                <div v-if="unidades.error" class="alert alert-danger" role="alert">
                    <h3>@{{ unidades.error }}</h3>
                </div>

                <div v-if="unidades.length > 0" class="table-responsive">
                    <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                        <thead>
                            <tr>
                                <th scope="col">Descripcion</th>
                                <th scope="col">Abreviatura</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- vue foreach -->
                            <tr v-for='unidad in unidades' :key="unidad.id">
                                <td v-if="unidad.descripcion.length > 15">
                                    @{{ unidad.descripcion.substring(0, 15) }}...
                                </td>
                                <td v-else>
                                    @{{ unidad.descripcion }}
                                </td>
                                <td>
                                    @{{ unidad.abreviatura }}
                                </td>
                                <td v-if="unidad.estado == 1">
                                    <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                    <span v-else class="badge bg-success">@{{ estados.find(estado => estado.id == unidad.estado).descripcion }}</span>
                                </td>
                                <td v-else>
                                    <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                    <span v-else class="badge bg-danger">@{{ estados.find(estado => estado.id == unidad.estado).descripcion }}</span>
                                </td>
                                <td>
                                    <button id="editBTN" class="btn btn-primary" @click="editUnidad(unidad)">
                                        <i class="fas fa-pencil"></i>
                                    </button>

                                    <button class="btn btn-danger" id="dltBTN" @click="DeleteUnidad(unidad)">
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
    <div class="modal fade" id="crearUnidadModal" tabindex="-1" aria-labelledby="crearUnidadModalLabel"
        aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                    <h1 class="modal-title fs-5" id="crearUnidadModalLabel">Crear unidad </h1>
                    <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <form ref="form" action="{{ route('unidades.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="descripcion" name="descripcion"
                                        placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion"
                                        value="{{ old('descripcion') }}">
                                    <label for="floatingInput">Descripcion*</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.descripcion">@{{ errors.descripcion }}</div>
                                </div>
                            </div>
                            <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="abreviatura" name="abreviatura"
                                        value="{{ old('abreviatura') }}" placeholder="Abreviatura"
                                        v-model="item.abreviatura" @blur="validateForm">
                                    <label for="floatingInput">Abreviatura*</label>
                                    <div class="invalid-tooltip"
                                        v-if="errors.abreviatura">@{{ errors.abreviatura }}</div>
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
    <div class="modal fade" id="editUnidadModal" tabindex="-1" aria-labelledby="editUnidadModalLabel"
        aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                    <h1 class="modal-title fs-5" id="editUnidadModalLabel">Editar unidad</h1>
                    <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <form ref="formEdit">
                        @csrf
                        <div class="row">
                            <div class="form-floating col-lg-4" style="margin-bottom: 10px;">

                                <div class="form-floating mb-3">
                                    <!-- Descripcion -->
                                    <input type="text" class="form-control" id="descripcionEdit"
                                        name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                        v-model="editItem.descripcion">
                                    <label for="floatingInput">Descripcion*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.descripcion">@{{ editErrors.descripcion }}</div>
                                </div>

                            </div>
                            <div class="form-floating col-lg-4" style="margin-bottom: 10px;">
                                <div class="form-floating mb-3">

                                    <!-- Abreviatura -->
                                    <input type="text" class="form-control" id="abreviaturaEdit"
                                        name="abreviatura" placeholder="Abreviatura" v-model="editItem.abreviatura"
                                        @blur="validateEditForm">
                                    <label for="floatingInput">Abreviatura*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.abreviatura">@{{ editErrors.abreviatura }}</div>
                                </div>
                            </div>
                            <div class="form-floating col-lg-4" style="margin-bottom: 10px;">
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
                                    <label for="floatingInput">Estado*</label>
                                    <div class="invalid-tooltip"
                                        v-if="editErrors.estado">@{{ editErrors.estado }}</div>

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
    <div class="modal fade" id="deleteUnidadModal" tabindex="-1" aria-labelledby="deleteUnidadModalLabel"
        aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                    <h1 class="modal-title fs-5" id="deleteUnidadModalLabel">Eliminar unidad</h1>
                    <small class="text-muted text-danger"> ¿Estas seguro de eliminar este unidad?</small>
                </div>
                <div class="modal-body text-center" style="padding: 25px;">
                    <h3>Descripcion: @{{ deleteItem.descripcion }}</h3>
                    <h3>Abreviatura: @{{ deleteItem.abreviatura }}</h3>
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
                descripcion: '',
                abreviatura: '',
            },
            editItem: {
                id: '',
                descripcion: '',
                abreviatura: '',
                estado: ''
            },
            deleteItem: {
                id: '',
                descripcion: '',
                abreviatura: '',
                estado: ''
            },
            search: '',
            errors: {},
            editErrors: {},
            unidades: [],
            searchUnidades: [],
            filtered: [],
            estados: [],
            loading: true,
            searchError: '',
            page: 1,
            per_page: 10,
            total: 0,
            totalPages: 0,
            nextPageUrl: '',
            prevPageUrl: '',
            mensaje: '',
            posiblesMensajes: [
                'Bienvenido a la sección de unidades',
                'Aqui puedes crear, editar y eliminar unidades',
                'Recuerda que los campos marcados con * son obligatorios',
                'Puedes buscar una unidad por su descripcion o abreviatura'
            ]
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
                        url: '/unidades/store',
                        data: this.item
                    }).then(response => {

                        //Habilitar boton
                        document.getElementById('SubmitForm').disabled = false;
                        document.getElementById('cancelButton').disabled = false;



                        //Cerrar modal
                        document.getElementById('cancelButton').click();

                        if (response.data.success) {
                            swal.fire({
                                title: 'Unidad creada',
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
                            text: 'Ha ocurrido un error al crear la unidad',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar unidades
                        this.getAllUnidades();
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
                        url: '/unidades/edit/' + this.editItem.id,
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
                                title: 'Unidad editada',
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
                            text: 'Ha ocurrido un error al editar la unidad',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar unidades
                        this.getAllUnidades();

                    })

                }
            },
            editUnidad(unidad) {
                this.editItem.descripcion = unidad.descripcion;
                this.editItem.abreviatura = unidad.abreviatura;
                this.editItem.estado = unidad.estado;
                this.editItem.id = unidad.id;

                //dar click al boton de modal
                document.getElementById('editUnidadModalBtn').click();

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
                    url: '/unidades/delete/' + this.deleteItem.id,
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
                            title: 'Unidad eliminado',
                            text: response.data.success,
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                        });
                    }

                }).catch(error => {
                    swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error al eliminar la unidad',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });

                }).finally(() => {

                    //limpiar
                    this.cleanForm();
                    //Recargar unidades
                    this.getAllUnidades();
                })


            },
            DeleteUnidad(unidad) {
                this.deleteItem.descripcion = unidad.descripcion;
                this.deleteItem.abreviatura = unidad.abreviatura;
                this.deleteItem.id = unidad.id;

                //dar click al boton de modal
                document.getElementById('deleteUnidadModalBtn').click();
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
                if (!this.item.abreviatura) {
                    this.errors.abreviatura = 'Este campo es obligatorio';
                    document.getElementById('abreviatura').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('abreviatura').setAttribute('class', 'form-control is-valid');
                }

                this.validateUnidadname();
            },
            validateEditForm() {

                this.editErrors = {};

                if (!this.editItem.descripcion) {
                    this.editErrors.descripcion = 'Este campo es obligatorio';
                    document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('descripcionEdit').setAttribute('class', 'form-control is-valid');
                }

                if (!this.editItem.abreviatura) {
                    this.editErrors.abreviatura = 'Este campo es obligatorio';
                    document.getElementById('abreviaturaEdit').setAttribute('class', 'form-control is-invalid');
                } else {
                    document.getElementById('abreviaturaEdit').setAttribute('class', 'form-control is-valid');
                }

                document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');

                this.validateEditUnidadname();
            },
            validateUnidadname() {
                //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                let regex = /^[a-zA-Z0-9]{3,}$/;

                if (!regex.test(this.item.descripcion)) {
                    this.errors.descripcion =
                        'La unidad debe tener al menos 3 caracteres y no contener espacios o caracteres especiales';
                }

                for (let i = 0; i < this.unidades.length; i++) {
                    if (this.unidades[i].descripcion == this.item.descripcion) {
                        this.errors.descripcion = 'La unidad ya existe';
                    }
                }
            },
            validateEditUnidadname() {

                this.editErrors = {};

                let regex = /^[a-zA-Z0-9]{3,}$/;

                if (!regex.test(this.editItem.descripcion)) {
                    this.editErrors.descripcion =
                        'La unidad debe tener al menos 3 caracteres y no contener espacios o caracteres especiales';
                }

                //Eliminar del array la unidad que se esta editando
                this.unidades = this.unidades.filter(unidad => unidad.id != this.editItem.id);

                //recorrer this.unidades
                for (let i = 0; i < this.unidades.length; i++) {
                    if (this.unidades[i].descripcion == this.editItem.descripcion) {
                        this.editErrors.descripcion = 'La unidad ya existe';
                    }
                }

            },
            //Limpiar formulario y busqueda
            cleanForm() {

                this.item = {
                    descripcion: '',
                    abreviatura: '',
                };

                this.errors = {};
                this.editErrors = {};
                this.search = '';
                //this.unidades = [];
                this.editItem = {
                    id: '',
                    descripcion: '',
                    abreviatura: '',
                    estado: ''
                };
                this.deleteItem = {
                    id: '',
                    descripcion: '',
                    abreviatura: '',
                    estado: ''
                };

                document.getElementById('descripcion').setAttribute('class', 'form-control');
                document.getElementById('abreviatura').setAttribute('class', 'form-control');

                document.getElementById('descripcionEdit').setAttribute('class', 'form-control');
                document.getElementById('abreviaturaEdit').setAttribute('class', 'form-control');
                document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                this.unidades = this.searchUnidades;
            },
            cleanSearch() {
                this.search = '';
                this.searchError = '';
                this.getAllUnidades();
            },
            //Paginacion
            pageMinus() {
                if (this.page > 1) {
                    this.page--;
                    this.getAllUnidades();
                }
            },
            pagePlus() {
                if (this.page < this.totalPages) {
                    this.page++;
                    this.getAllUnidades();
                }
            },
            specificPage(page) {
                this.page = page;
                this.getAllUnidades();
            },
            changePerPage() {
                this.page = 1;
                this.getAllUnidades();
            },
            //Obtener recursos
            async getAllUnidades() {

                try {
                    axios({
                        method: 'get',
                        url: '/allUnidades',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            search: this.search
                        }
                    }).then(response => {
                        this.loading = false;
                        this.unidades = response.data.data;
                        this.searchUnidades = response.data.data;

                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;
                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.getAllUnidades();
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
                            text: 'Ha ocurrido un error al obtener las unidades',
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
            //Misc
            randomMessage() {
                let random = Math.floor(Math.random() * this.posiblesMensajes.length);
                this.mensaje = this.posiblesMensajes[random];
            },

        },
        mounted() {
            this.getAllEstados();
            this.getAllUnidades();
            this.randomMessage();

        }
    });
</script>
@endsection
