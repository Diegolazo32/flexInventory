@extends('Layouts.Navigation')

@section('title', 'Clientes')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Clientes</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crearClienteModal" style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editClienteModalBtn"
                            data-bs-target="#editClienteModal" style="height: 40px;" hidden>
                            Editar cliente
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteClienteModalBtn"
                            data-bs-target="#deleteClienteModal" style="height: 40px;" hidden>
                            Eliminar cliente
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="showClienteModalBtn"
                            data-bs-target="#showClienteModal" style="height: 40px;" hidden>
                            Ver cliente
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
                                    placeholder="Buscar por nombre, apellido o DUI" v-model="search">
                                <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllClientes"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de clientes -->

            <div class="row">
                <div class="card-body">


                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="clientes.error" class="alert alert-danger" role="alert">
                        <h3>@{{ clientes.error }}</h3>
                    </div>

                    <div v-if="clientes.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">DUI</th>
                                    <th scope="col">Descuento (%)</th>
                                    <th scope="col">Fecha de creacion</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='cliente in clientes' :key="cliente.id">
                                    <td>@{{ cliente.nombre }}</td>
                                    <td>@{{ cliente.apellido }}</td>
                                    <td>@{{ cliente.telefono }}</td>
                                    <td>@{{ cliente.email }}</td>
                                    <td>@{{ cliente.DUI }}</td>
                                    <td>@{{ cliente.descuento }}%</td>
                                    <td>@{{ formatDate(cliente.created_at) }}</td>
                                    <td v-if="cliente.estado == 1">
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-success">@{{ estados.find(estado => estado.id == cliente.estado).descripcion }}</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-danger">@{{ estados.find(estado => estado.id == cliente.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editCliente(cliente)"
                                            :disabled="loading">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <button id="viewBTN" class="btn btn-warning" @click="viewCliente(cliente)"
                                            :disabled="loading">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteCliente(cliente)"
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
        <div class="modal fade" id="crearClienteModal" tabindex="-1" aria-labelledby="crearClienteModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearClienteModalLabel">Crear cliente </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('clientes.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <div class="invalid-tooltip" v-if="errors.nombre">@{{ errors.nombre }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="apellido" name="apellido"
                                            placeholder="Apellido" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.apellido">
                                        <label for="floatingInput">Apellido*</label>
                                        <div class="invalid-tooltip" v-if="errors.apellido">@{{ errors.apellido }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefono" name="telefono"
                                            placeholder="Telefono" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.telefono">
                                        <label for="floatingInput">Telefono*</label>
                                        <div class="invalid-tooltip" v-if="errors.telefono">@{{ errors.telefono }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Email" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.email">
                                        <label for="floatingInput">Email*</label>
                                        <div class="invalid-tooltip" v-if="errors.email">@{{ errors.email }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="DUI" name="DUI"
                                            placeholder="DUI" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.DUI" maxlength="10">
                                        <label for="floatingInput">DUI*</label>
                                        <div class="invalid-tooltip" v-if="errors.DUI">@{{ errors.DUI }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="descuento" name="descuento"
                                            placeholder="Descuento" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.descuento" maxlength="2" min="0" max="100">
                                        <label for="floatingInput">Descuento*</label>
                                        <div class="invalid-tooltip" v-if="errors.descuento">@{{ errors.descuento }}</div>
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
        <div class="modal fade" id="editClienteModal" tabindex="-1" aria-labelledby="editClienteModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editClienteModalLabel">Editar cliente</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.nombre">@{{ editErrors.nombre }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="apellidoEdit" name="apellido"
                                            placeholder="Apellido" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.apellido">
                                        <label for="floatingInput">Apellido*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.apellido">@{{ editErrors.apellido }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoEdit" name="telefono"
                                            placeholder="Telefono" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.telefono">
                                        <label for="floatingInput">Telefono*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.telefono">@{{ editErrors.telefono }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="emailEdit" name="email"
                                            placeholder="Email" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.email">
                                        <label for="floatingInput">Email*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.email">Error</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="DUIEdit" name="DUI"
                                            placeholder="DUI" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.DUI" maxlength="10">
                                        <label for="floatingInput">DUI*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.DUI">@{{ editErrors.DUI }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="descuentoEdit" name="descuento"
                                            placeholder="Descuento" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.descuento" maxlength="2" min="0" max="100">
                                        <label for="floatingInput">Descuento*</label>
                                        <div class="invalid-tooltip" v-if="editErrors.descuento">@{{ editErrors.descuento }}
                                        </div>
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
                                        <label for="floatingInput">Estado*</label>
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
        <div class="modal fade" id="deleteClienteModal" tabindex="-1" aria-labelledby="deleteClienteModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteClienteModalLabel">Eliminar cliente</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este cliente?</small>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h4>Nombre: @{{ deleteItem.nombre }}</h4>
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

        <!--Show modal-->
        <div class="modal fade" id="showClienteModal" tabindex="-1" aria-labelledby="showClienteModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="showClienteModalLabel">Cliente</h1>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h4>Nombre:</h4>
                        <p>@{{ showItem.nombre }}</p>
                        <h4>Apellido:</h4>
                        <p>@{{ showItem.apellido }}</p>
                        <h4>Telefono:</h4>
                        <p>@{{ showItem.telefono }}</p>
                        <h4>Email:</h4>
                        <p>@{{ showItem.email }}</p>
                        <h4>DUI:</h4>
                        <p>@{{ showItem.DUI }}</p>
                        <h4>Descuento:</h4>
                        <p>@{{ showItem.descuento }}%</p>
                        <h4>Estado:</h4>
                        <p>@{{ showItem.estado }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelShowButton"
                            @click="cleanForm">Cerrar</button>
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
                    nombre: null,
                    apellido: null,
                    telefono: null,
                    email: null,
                    DUI: null,
                    descuento: null,
                    estado: null,
                },
                editItem: {
                    id: null,
                    nombre: null,
                    apellido: null,
                    telefono: null,
                    email: null,
                    DUI: null,
                    descuento: 0 || null,
                    estado: null,
                },
                deleteItem: {
                    id: null,
                    nombre: null,
                    apellido: null,
                    telefono: null,
                    email: null,
                    DUI: null,
                    descuento: null,
                    estado: null,
                },
                showItem: {
                    id: null,
                    nombre: null,
                    apellido: null,
                    telefono: null,
                    email: null,
                    DUI: null,
                    descuento: null,
                    estado: null,
                },
                search: null,
                errors: {},
                editErrors: {},
                clientes: [],
                searchClientes: [],
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
                            '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButton').disabled = true;

                        axios({
                            method: 'post',
                            url: '/clientes/store',
                            data: this.item
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;


                            //Cerrar modal
                            document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Cliente creado',
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
                                text: 'Ha ocurrido un error al crear el cliente',
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
                            //Recargar clientes
                            this.getAllClientes();
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
                            url: '/clientes/edit/' + this.editItem.id,
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
                                    title: 'Cliente editado',
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
                            document.getElementById('SubmitFormEdit').disabled = false;
                            document.getElementById('cancelButtonEdit').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitFormEdit').innerHTML =
                                'Guardar';

                            document.getElementById('cancelButtonEdit').click();

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al editar el cliente',
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
                            //Recargar clientes
                            this.getAllClientes();

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
                editCliente(cliente) {

                    this.editItem.nombre = cliente.nombre;
                    this.editItem.apellido = cliente.apellido;
                    this.editItem.telefono = cliente.telefono;
                    this.editItem.email = cliente.email;
                    this.editItem.DUI = cliente.DUI;
                    this.editItem.descuento = cliente.descuento;
                    this.editItem.estado = cliente.estado;
                    this.editItem.id = cliente.id;

                    //dar click al boton de modal
                    document.getElementById('editClienteModalBtn').click();

                },
                //Show
                viewCliente(cliente) {

                    this.showItem.nombre = cliente.nombre;
                    this.showItem.apellido = cliente.apellido;
                    this.showItem.telefono = cliente.telefono;
                    this.showItem.email = cliente.email;
                    this.showItem.DUI = cliente.DUI;
                    this.showItem.descuento = cliente.descuento;
                    this.showItem.estado = cliente.estado;
                    this.showItem.id = cliente.id;


                    //dar click al boton de modal
                    document.getElementById('showClienteModalBtn').click();

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
                        url: '/clientes/delete/' + this.deleteItem.id,
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
                                title: 'Cliente eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        }

                    }).catch(error => {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al eliminar el cliente',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar clientes
                        this.getAllClientes();
                    })


                },
                DeleteCliente(cliente) {
                    this.deleteItem.nombre = cliente.nombre;

                    this.deleteItem.id = cliente.id;

                    //dar click al boton de modal
                    document.getElementById('deleteClienteModalBtn').click();
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

                    if (!this.item.apellido) {
                        this.errors.apellido = 'Este campo es obligatorio';
                        document.getElementById('apellido').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('apellido').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.telefono) {
                        this.errors.telefono = 'Este campo es obligatorio';
                        document.getElementById('telefono').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('telefono').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.email) {
                        this.errors.email = 'Este campo es obligatorio';
                        document.getElementById('email').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('email').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.DUI) {
                        this.errors.DUI = 'Este campo es obligatorio';
                        document.getElementById('DUI').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('DUI').setAttribute('class', 'form-control is-valid');
                    }

                    this.validateClientename();
                    this.validateDescuento();
                },
                validateEditForm() {

                    this.editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.apellido) {
                        this.editErrors.apellido = 'Este campo es obligatorio';
                        document.getElementById('apellidoEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('apellidoEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.telefono) {
                        this.editErrors.telefono = 'Este campo es obligatorio';
                        document.getElementById('telefonoEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('telefonoEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.email) {
                        this.editErrors.email = 'Este campo es obligatorio';
                        document.getElementById('emailEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('emailEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.DUI) {
                        this.editErrors.DUI = 'Este campo es obligatorio';
                        document.getElementById('DUIEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('DUIEdit').setAttribute('class', 'form-control is-valid');
                    }

                    document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');

                    this.validateEditClientename();
                    this.validateDescuentoEdit();
                },
                validateClientename() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9.]{3,}$/;

                    if (!regex.test(this.item.nombre)) {
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                        this.errors.nombre =
                            'El cliente debe tener al menos 3 caracteres y no contener caracteres especiales';
                    }

                    for (let i = 0; i < this.clientes.length; i++) {
                        if (this.clientes[i].nombre == this.item.nombre) {
                            document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                            this.errors.nombre = 'El cliente ya existe';
                        }
                    }
                },
                validateEditClientename() {

                    //Al menos 5 caracteres, con espacios y puntos  .!@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9.]{3,}$/;

                    if (!regex.test(this.editItem.nombre)) {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                        this.editErrors.nombre =
                            'El cliente debe tener al menos 3 caracteres y no contener caracteres especiales';
                    }

                    //Eliminar del array el cliente que se esta editando
                    this.clientes = this.clientes.filter(cliente => cliente.id != this.editItem.id);

                    //recorrer this.clientes
                    for (let i = 0; i < this.clientes.length; i++) {
                        if (this.clientes[i].nombre == this.editItem.nombre) {
                            document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                            this.editErrors.nombre = 'El cliente ya existe';
                        }
                    }

                },
                validateDescuento() {

                    parseInt(this.item.descuento)

                    if (!this.item.descuento) {
                        this.errors.descuento = 'Este campo es obligatorio';
                        document.getElementById('descuento').setAttribute('class', 'form-control is-invalid');
                        return;
                    }

                    if (this.item.descuento < 0 || this.item.descuento > 100) {
                        document.getElementById('descuento').setAttribute('class', 'form-control is-invalid');
                        this.errors.descuento = 'El descuento debe ser mayor o igual a 0 y menor a 100';
                        return;
                    }

                    document.getElementById('descuento').setAttribute('class', 'form-control is-valid');


                },
                validateDescuentoEdit() {
                    console.log(this.editItem.descuento)

                    if (this.editItem.descuento === null) {
                        this.editErrors.descuento = 'Este campo es obligatorio';
                        document.getElementById('descuentoEdit').setAttribute('class', 'form-control is-invalid');
                        return;
                    }

                    parseInt(this.editItem.descuento);

                    if (this.editItem.descuento < 0 || this.editItem.descuento > 100) {
                        document.getElementById('descuentoEdit').setAttribute('class', 'form-control is-invalid');
                        this.editErrors.descuento = 'El descuento debe ser mayor o igual a 0 y menor a 100';
                        return;
                    }

                    document.getElementById('descuentoEdit').setAttribute('class', 'form-control is-valid');

                },
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllClientes();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllClientes();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllClientes();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllClientes();
                },
                //Limpiar formulario y busqueda
                cleanForm() {

                    this.item = {
                        nombre: null,
                        apellido: null,
                        telefono: null,
                        email: null,
                        DUI: null,
                        descuento: null,
                        estado: null,
                    };

                    this.errors = {};
                    this.editErrors = {};
                    this.search = null;
                    //this.clientes = [];
                    this.editItem = {
                        id: null,
                        nombre: null,
                        apellido: null,
                        telefono: null,
                        email: null,
                        DUI: null,
                        descuento: null,
                        estado: null,
                        estado: null,
                    };
                    this.deleteItem = {
                        id: null,
                        nombre: null,
                        apellido: null,
                        telefono: null,
                        email: null,
                        DUI: null,
                        descuento: null,
                        estado: null,
                        estado: null,
                    };

                    this.showItem = {
                        id: null,
                        nombre: null,
                        apellido: null,
                        telefono: null,
                        email: null,
                        DUI: null,
                        descuento: null,
                        estado: null,
                        estado: null,
                    };

                    document.getElementById('nombre').setAttribute('class', 'form-control');
                    document.getElementById('apellido').setAttribute('class', 'form-control');
                    document.getElementById('telefono').setAttribute('class', 'form-control');
                    document.getElementById('email').setAttribute('class', 'form-control');
                    document.getElementById('DUI').setAttribute('class', 'form-control');
                    document.getElementById('descuento').setAttribute('class', 'form-control');

                    document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                    document.getElementById('apellidoEdit').setAttribute('class', 'form-control');
                    document.getElementById('telefonoEdit').setAttribute('class', 'form-control');
                    document.getElementById('emailEdit').setAttribute('class', 'form-control');
                    document.getElementById('DUIEdit').setAttribute('class', 'form-control');
                    document.getElementById('descuentoEdit').setAttribute('class', 'form-control');
                    document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                    this.clientes = this.searchClientes;
                },
                cleanSearch() {
                    this.search = null;
                    this.searchError = '';
                    this.getAllClientes();
                },
                formatDate(date) {
                    let dateObj = new Date(date);
                    let month = dateObj.getUTCMonth() + 1;
                    let day = dateObj.getUTCDate();
                    let year = dateObj.getUTCFullYear();

                    return day + "/" + month + "/" + year;
                },
                //Obtener recursos
                async getAllClientes() {

                    this.loading = true;
                    this.errors = {};
                    this.editErrors = {};

                    axios({
                        method: 'get',
                        url: '/allClientes',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            search: this.search
                        }
                    }).then(response => {
                        this.loading = false;
                        this.clientes = response.data.data;
                        this.searchClientes = response.data.data;

                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;
                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.getAllClientes();
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
                            text: 'Ha ocurrido un error al obtener los clientes',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    })
                },
                async getAllEstados() {

                    try {
                        let response = await fetch('/allEstados');
                        let data = await response.json();
                        this.estados = data;
                    } catch (error) {

                    }


                },

            },
            mounted() {
                this.getAllEstados();
                this.getAllClientes();
            }
        });
    </script>
@endsection
