@extends('Layouts.Navigation')

@section('title', 'Cajas')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Cajas</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearCajaModal"
                            style="height: 40px;">
                            <i class="fas fa-plus"></i>
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
                                <input type="text" class="form-control" name="search" placeholder="Buscar por nombre"
                                    v-model="search" @keyup.enter="getAllCajas">
                                <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllCajas"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de cajas -->
            <div class="row">
                <div class="card-body">


                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="cajas.error" class="alert alert-danger" role="alert">
                        <h3>@{{ cajas.error }}</h3>
                    </div>

                    <div v-if="cajas.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Ubicacion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='caja in cajas' :key="caja.id">
                                    <td v-if="caja.nombre.length > 15">
                                        @{{ caja.nombre.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ caja.nombre }}
                                    </td>
                                    <td>
                                        @{{ caja.ubicacion }}
                                    </td>
                                    <td v-if="caja.estado == 1">
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-success">@{{ estados.find(estado => estado.id == caja.estado).descripcion }}</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-danger">@{{ estados.find(estado => estado.id == caja.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" data-bs-toggle="modal"
                                            id="editCajaModalBtn" data-bs-target="#editCajaModal" @click="editCaja(caja)"
                                            :disabled="loading">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" data-bs-toggle="modal"
                                            id="deleteCajaModalBtn" data-bs-target="#deleteCajaModal"
                                            @click="DeleteCaja(caja)" :disabled="loading">
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
        <div class="modal fade" id="crearCajaModal" tabindex="-1" aria-labelledby="crearCajaModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearCajaModalLabel">Crear caja </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('cajas.store') }}" method="POST"
                            @submit.prevent="sendForm">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.nombre">
                                        <label for="floatingInput">Nombre<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.nombre">@{{ errors.nombre }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                            placeholder="Ubicacion" v-model="item.ubicacion" @blur="validateForm"
                                            @keyup="validateForm" @keyup.enter="sendForm">
                                        <label for="floatingInput">Ubicacion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.ubicacion">@{{ errors.ubicacion }}</div>
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
        <div class="modal fade" id="editCajaModal" tabindex="-1" aria-labelledby="editCajaModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editCajaModalLabel">Editar caja</h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit" @submit.prevent="sendFormEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-4" style="margin-bottom: 10px;">

                                    <div class="form-floating mb-3">
                                        <!-- Nombre -->
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.nombre">@{{ editErrors.nombre }}
                                        </div>
                                    </div>

                                </div>
                                <div class="form-floating col-lg-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Ubicacion -->
                                        <input type="text" class="form-control" id="ubicacionEdit" name="ubicacion"
                                            placeholder="Ubicacion" v-model="editItem.ubicacion" @blur="validateEditForm"
                                            @keyup="validateEditForm" @keyup.enter="sendFormEdit">
                                        <label for="floatingInput">Ubicacion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.ubicacion">@{{ editErrors.ubicacion }}
                                        </div>
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
        <div class="modal fade" id="deleteCajaModal" tabindex="-1" aria-labelledby="deleteCajaModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteCajaModalLabel">Eliminar caja</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este caja?</small>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h3>Nombre: @{{ deleteItem.nombre }}</h3>
                        <h3>Ubicacion: @{{ deleteItem.ubicacion }}</h3>
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
                    ubicacion: '',
                },
                editItem: {
                    id: '',
                    nombre: '',
                    ubicacion: '',
                    estado: ''
                },
                deleteItem: {
                    id: '',
                    nombre: '',
                    ubicacion: '',
                    estado: ''
                },
                search: '',
                errors: {},
                editErrors: {},
                cajas: [],
                searchCajas: [],
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
                            url: '/cajas/store',
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
                                    title: 'Caja creada',
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
                                text: 'Ha ocurrido un error al crear la caja',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                'Guardar';

                            //limpiar
                            this.cleanForm();
                            //Recargar cajas
                            this.getAllCajas();
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
                            url: '/cajas/edit/' + this.editItem.id,
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
                                    title: 'Caja actualizada',
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
                                text: 'Ha ocurrido un error al editar la caja',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //Habilitar boton
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitFormEdit').innerHTML = 'Guardar';

                            //limpiar
                            this.cleanForm();
                            //Recargar cajas
                            this.getAllCajas();

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
                editCaja(caja) {
                    this.editItem.nombre = caja.nombre;
                    this.editItem.ubicacion = caja.ubicacion;
                    this.editItem.estado = caja.estado;
                    this.editItem.id = caja.id;

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
                        url: '/cajas/delete/' + this.deleteItem.id,
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
                                title: 'Caja eliminado',
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
                            text: 'Ha ocurrido un error al eliminar la caja',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //Habilitar boton
                        document.getElementById('deleteButton').disabled = false;
                        document.getElementById('canceldeleteButton').disabled = false;

                        //Quitar icono de boton
                        document.getElementById('deleteButton').innerHTML = 'Eliminar';

                        //limpiar
                        this.cleanForm();
                        //Recargar cajas
                        this.getAllCajas();
                    })


                },
                DeleteCaja(caja) {
                    this.deleteItem.nombre = caja.nombre;
                    this.deleteItem.ubicacion = caja.ubicacion;
                    this.deleteItem.id = caja.id;
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
                    if (!this.item.ubicacion) {
                        this.errors.ubicacion = 'Este campo es obligatorio';
                        document.getElementById('ubicacion').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('ubicacion').setAttribute('class', 'form-control is-valid');
                    }

                    this.validateCajaname();
                },
                validateEditForm() {

                    this.editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.ubicacion) {
                        this.editErrors.ubicacion = 'Este campo es obligatorio';
                        document.getElementById('ubicacionEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('ubicacionEdit').setAttribute('class', 'form-control is-valid');
                    }

                    document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');

                    this.validateEditCajaname();
                },
                validateCajaname() {
                    //Al menos 5 caracteres y sin  caracteres especiales, !@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.item.nombre)) {

                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                        this.errors.nombre =
                            'La caja debe tener al menos 3 caracteres y no contener  caracteres especiales';
                    }

                    for (let i = 0; i < this.cajas.length; i++) {
                        if (this.cajas[i].nombre == this.item.nombre) {
                            document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');

                            this.errors.nombre = 'La caja ya existe';
                        }
                    }
                },
                validateEditCajaname() {

                    this.editErrors = {};

                    let regex = /^[ a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.editItem.nombre)) {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                        this.editErrors.nombre =
                            'La caja debe tener al menos 3 caracteres y no contener  caracteres especiales';
                    }

                    //Eliminar del array la caja que se esta editando
                    this.cajas = this.cajas.filter(caja => caja.id != this.editItem.id);

                    //recorrer this.cajas
                    for (let i = 0; i < this.cajas.length; i++) {
                        if (this.cajas[i].nombre == this.editItem.nombre) {
                            document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                            this.editErrors.nombre = 'La caja ya existe';
                        }
                    }

                },
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllCajas();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllCajas();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllCajas();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllCajas();
                },
                //Limpiar formulario y busqueda
                cleanForm() {

                    this.item = {
                        nombre: '',
                        ubicacion: '',
                    };

                    this.errors = {};
                    this.editErrors = {};
                    this.search = '';
                    //this.cajas = [];
                    this.editItem = {
                        id: '',
                        nombre: '',
                        ubicacion: '',
                        estado: ''
                    };
                    this.deleteItem = {
                        id: '',
                        nombre: '',
                        ubicacion: '',
                        estado: ''
                    };

                    document.getElementById('nombre').setAttribute('class', 'form-control');
                    document.getElementById('ubicacion').setAttribute('class', 'form-control');

                    document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                    document.getElementById('ubicacionEdit').setAttribute('class', 'form-control');
                    document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                    this.cajas = this.searchCajas;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.getAllCajas();
                },
                //Obtener recursos
                async getAllCajas() {

                    this.loading = true;
                    this.errors = {};
                    this.editErrors = {};

                    try {
                        axios({
                            method: 'get',
                            url: '/allCajas',
                            params: {
                                page: this.page,
                                per_page: this.per_page,
                                search: this.search
                            }
                        }).then(response => {
                            this.loading = false;
                            this.cajas = response.data.data;
                            this.searchCajas = response.data.data;

                            this.total = response.data.total;
                            this.totalPages = response.data.last_page;

                            if (this.page > this.totalPages) {
                                this.page = 1;
                                this.getAllCajas();
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
                                text: 'Ha ocurrido un error al obtener las cajas',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        })

                    } catch (error) {

                        this.loading = false;
                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            title: 'Error',
                            text: 'Ha ocurrido un error al obtener las cajas',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }
                },
                async getAllEstados() {

                    try {
                        let response = await fetch('/allEstados');
                        let data = await response.json();

                        this.estados = data;

                    } catch (error) {

                        swal.fire({
                            toast: true,
                            showCloseButton: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
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
                this.getAllCajas();

            }
        });
    </script>
@endsection
