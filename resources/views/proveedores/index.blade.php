@extends('Layouts.Navigation')

@section('title', 'Proveedores')

@section('content')
    <div id="app">
        <div class="card hoverCard">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-10">
                        <h1>Proveedores</h1>
                        <small class="text-muted"></small>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-lg-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crearProveedorModal" style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editProveedorModalBtn"
                            data-bs-target="#editProveedorModal" style="height: 40px;" hidden>
                            Editar proveedor
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteProveedorModalBtn"
                            data-bs-target="#deleteProveedorModal" style="height: 40px;" hidden>
                            Eliminar proveedor
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="showProveedorModalBtn"
                            data-bs-target="#showProveedorModal" style="height: 40px;" hidden>
                            Ver proveedor
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
                                <input type="text" class="form-control" name="search" @keyup.enter="getAllProveedores"
                                    placeholder="Buscar por nombre, NIT, representante o email" v-model="search">
                                <div class="invalid-tooltip" v-if="searchError">@{{ searchError }}</div>
                            </div>
                            <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                <button class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="getAllProveedores"><i class="fa-solid fa-magnifying-glass"></i></button>
                                <button v-if="search" class="btn btn-primary" style="height: 40px; max-height: 40px;"
                                    @click="cleanSearch"><i class="fa-solid fa-filter-circle-xmark"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Tabla de proveedores -->
            <div class="row">
                <div class="card-body">

                    <div v-if="loading" role="alert" style="display: flex; justify-content: center; align-items: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-if="proveedores.error" class="alert alert-danger" role="alert">
                        <h3>@{{ proveedores.error }}</h3>
                    </div>

                    <div v-if="proveedores.length > 0" class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Direccion</th>
                                    <th scope="col">NIT</th>
                                    <th scope="col">Email Principal</th>
                                    <th scope="col">Telefono Principal</th>
                                    <th scope="col">Representante</th>
                                    <th scope="col">Email Representante</th>
                                    <th scope="col">Telefono Representante</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='proveedor in proveedores' :key="proveedor.id">
                                    <td v-if="proveedor.nombre.length > 15">
                                        @{{ proveedor.nombre.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.nombre }}
                                    </td>
                                    <td v-if="proveedor.direccion.length > 15">
                                        @{{ proveedor.direccion.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.direccion }}
                                    </td>
                                    <td v-if="proveedor.NIT.length > 15">
                                        @{{ proveedor.NIT.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.NIT }}
                                    </td>
                                    <td v-if="proveedor.emailPrincipal.length > 15">
                                        @{{ proveedor.emailPrincipal.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.emailPrincipal }}
                                    </td>
                                    <td v-if="proveedor.telefonoPrincipal.length > 15">
                                        @{{ proveedor.telefonoPrincipal.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.telefonoPrincipal }}
                                    </td>
                                    <td v-if="proveedor.representante.length > 15">
                                        @{{ proveedor.representante.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.representante }}
                                    </td>
                                    <td v-if="proveedor.emailRepresentante.length > 15">
                                        @{{ proveedor.emailRepresentante.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.emailRepresentante }}
                                    </td>
                                    <td v-if="proveedor.telefonoRepresentante.length > 15">
                                        @{{ proveedor.telefonoRepresentante.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ proveedor.telefonoRepresentante }}
                                    </td>
                                    <td v-if="proveedor.estado == 1">
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-success">@{{ estados.find(estado => estado.id == proveedor.estado).descripcion }}</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger" v-if="estados.error">@{{ estados.error }}</span>
                                        <span v-else class="badge bg-danger">@{{ estados.find(estado => estado.id == proveedor.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editProveedor(proveedor)"
                                            :disabled="loading">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <button id="viewBTN" class="btn btn-warning" @click="viewProveedor(proveedor)"
                                            :disabled="loading">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteProveedor(proveedor)"
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
        <div class="modal fade" id="crearProveedorModal" tabindex="-1" aria-labelledby="crearProveedorModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearProveedorModalLabel">Crear proveedor </h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('proveedores.store') }}" method="POST"
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
                                        <input type="text" class="form-control" id="direccion" name="direccion"
                                            placeholder="Direccion" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.direccion">
                                        <label for="floatingInput">Direccion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.direccion">@{{ errors.direccion }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="NIT" name="NIT"
                                            placeholder="NIT" @blur="validateForm" @keyup="validateForm"
                                            v-model="item.NIT">
                                        <label for="floatingInput">NIT<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.NIT">@{{ errors.NIT }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="emailPrincipal"
                                            name="emailPrincipal" placeholder="EmailPrincipal" @blur="validateForm"
                                            @keyup="validateForm" v-model="item.emailPrincipal">
                                        <label for="floatingInput">Email Principal<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.emailPrincipal">@{{ errors.emailPrincipal }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoPrincipal"
                                            name="telefonoPrincipal" placeholder="TelefonoPrincipal" @blur="validateForm"
                                            @keyup="validateForm" v-model="item.telefonoPrincipal">
                                        <label for="floatingInput">Telefono Principal<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.telefonoPrincipal">
                                            @{{ errors.telefonoPrincipal }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="representante"
                                            name="representante" placeholder="Representante" @blur="validateForm"
                                            @keyup="validateForm" v-model="item.representante">
                                        <label for="floatingInput">Representante<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.representante">@{{ errors.representante }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="emailRepresentante"
                                            name="emailRepresentante" placeholder="Email Representante"
                                            @blur="validateForm" @keyup="validateForm" v-model="item.emailRepresentante">
                                        <label for="floatingInput">Email Representante<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.emailRepresentante">
                                            @{{ errors.emailRepresentante }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoRepresentante"
                                            name="telefonoRepresentante" placeholder="Telefono Representante"
                                            @blur="validateForm" @keyup="validateForm" @keyup.enter="sendForm"
                                            v-model="item.telefonoRepresentante">
                                        <label for="floatingInput">Telefono Representante<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="errors.telefonoRepresentante">
                                            @{{ errors.telefonoRepresentante }}</div>
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
        <div class="modal fade" id="editProveedorModal" tabindex="-1" aria-labelledby="editProveedorModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editProveedorModalLabel">Editar proveedor</h1>
                        <small class="text-muted"> Los campos marcados con <span class="text-danger">*</span> son
                            obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit" @submit.prevent="sendFormEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombreEdit" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.nombre">
                                        <label for="floatingInput">Nombre<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.nombre">@{{ editErrors.nombre }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="direccionEdit" name="direccion"
                                            placeholder="Direccion" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.direccion">
                                        <label for="floatingInput">Direccion<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.direccion">@{{ editErrors.direccion }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="NITEDIT" name="NIT"
                                            placeholder="Direccion" @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.NIT">
                                        <label for="floatingInput">NIT<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.NIT">@{{ editErrors.NIT }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="emailPrincipalEdit"
                                            name="emailPrincipal" placeholder="EmailPrincipal" @blur="validateEditForm"
                                            @keyup="validateEditForm" v-model="editItem.emailPrincipal">
                                        <label for="floatingInput">Email Principal<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.emailPrincipal">
                                            @{{ editErrors.emailPrincipal }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoPrincipalEdit"
                                            name="telefonoPrincipal" placeholder="TelefonoPrincipal"
                                            @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.telefonoPrincipal">
                                        <label for="floatingInput">Telefono Principal<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.telefonoPrincipal">
                                            @{{ editErrors.telefonoPrincipal }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="representanteEdit"
                                            name="representante" placeholder="Representante" @blur="validateEditForm"
                                            @keyup="validateEditForm" v-model="editItem.representante">
                                        <label for="floatingInput">Representante<span class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.representante">
                                            @{{ editErrors.representante }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="emailRepresentanteEdit"
                                            name="emailRepresentante" placeholder="Email Representante"
                                            @blur="validateEditForm" @keyup="validateEditForm"
                                            v-model="editItem.emailRepresentante">
                                        <label for="floatingInput">Email Representante<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.emailRepresentante">
                                            @{{ editErrors.emailRepresentante }}</div>
                                    </div>
                                </div>
                                <div class="form-floating col-lg-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="telefonoRepresentanteEdit"
                                            name="telefonoRepresentante" placeholder="Telefono Representante"
                                            @blur="validateEditForm" @keyup="validateEditForm"
                                            @keyup.enter="sendFormEdit" v-model="editItem.telefonoRepresentante">
                                        <label for="floatingInput">Telefono Representante<span
                                                class="text-danger">*</span></label>
                                        <div class="invalid-tooltip" v-if="editErrors.telefonoRepresentante">
                                            @{{ editErrors.telefonoRepresentante }}</div>
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
        <div class="modal fade" id="deleteProveedorModal" tabindex="-1" aria-labelledby="deleteProveedorModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteProveedorModalLabel">Eliminar proveedor</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este proveedor?</small>
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
        <div class="modal fade" id="showProveedorModal" tabindex="-1" aria-labelledby="showProveedorModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl modal-fullscreen-lg-down ">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="showProveedorModalLabel">Proveedor</h1>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <h4>Nombre:</h4>
                        <p>@{{ showItem.nombre }}</p>
                        <h4>Direccion:</h4>
                        <p>@{{ showItem.direccion }}</p>
                        <h4>NIT:</h4>
                        <p>@{{ showItem.NIT }}</p>
                        <h4>Email Principal:</h4>
                        <p>@{{ showItem.emailPrincipal }}</p>
                        <h4>Telefono Principal:</h4>
                        <p>@{{ showItem.telefonoPrincipal }}</p>
                        <h4>Representante:</h4>
                        <p>@{{ showItem.representante }}</p>
                        <h4>Email Representante:</h4>
                        <p>@{{ showItem.emailRepresentante }}</p>
                        <h4>Telefono Representante:</h4>
                        <p>@{{ showItem.telefonoRepresentante }}</p>
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
                    nombre: '',
                    direccion: '',
                    NIT: '',
                    emailPrincipal: '',
                    emailRepresentante: '',
                    representante: '',
                    telefonoPrincipal: '',
                    telefonoRepresentante: '',
                },
                editItem: {
                    id: '',
                    nombre: '',
                    direccion: '',
                    NIT: '',
                    emailPrincipal: '',
                    emailRepresentante: '',
                    representante: '',
                    telefonoPrincipal: '',
                    telefonoRepresentante: '',
                    estado: '',
                },
                deleteItem: {
                    id: '',
                    nombre: '',
                    direccion: '',
                    NIT: '',
                    emailPrincipal: '',
                    emailRepresentante: '',
                    representante: '',
                    telefonoPrincipal: '',
                    telefonoRepresentante: '',
                    estado: '',
                },
                showItem: {
                    id: '',
                    nombre: '',
                    direccion: '',
                    NIT: '',
                    emailPrincipal: '',
                    emailRepresentante: '',
                    representante: '',
                    telefonoPrincipal: '',
                    telefonoRepresentante: '',
                    estado: '',
                },
                search: '',
                errors: {},
                editErrors: {},
                proveedores: [],
                searchProveedores: [],
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
                            url: '/proveedores/store',
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
                                    title: 'Proveedor creado',
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
                                text: 'Ha ocurrido un error al crear el proveedor',
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
                            //Recargar proveedores
                            this.getAllProveedores();
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
                            url: '/proveedores/edit/' + this.editItem.id,
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
                                    title: 'Proveedor creado',
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
                                text: 'Ha ocurrido un error al editar el proveedor',
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
                            //Recargar proveedores
                            this.getAllProveedores();

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
                editProveedor(proveedor) {

                    this.editItem.nombre = proveedor.nombre;
                    this.editItem.direccion = proveedor.direccion;
                    this.editItem.NIT = proveedor.NIT;
                    this.editItem.emailPrincipal = proveedor.emailPrincipal;
                    this.editItem.telefonoPrincipal = proveedor.telefonoPrincipal;
                    this.editItem.representante = proveedor.representante;
                    this.editItem.emailRepresentante = proveedor.emailRepresentante;
                    this.editItem.telefonoRepresentante = proveedor.telefonoRepresentante;
                    this.editItem.estado = proveedor.estado;
                    this.editItem.id = proveedor.id;

                    //dar click al boton de modal
                    document.getElementById('editProveedorModalBtn').click();

                },
                //Show
                viewProveedor(proveedor) {

                    this.showItem.nombre = proveedor.nombre;
                    this.showItem.direccion = proveedor.direccion;
                    this.showItem.NIT = proveedor.NIT;
                    this.showItem.emailPrincipal = proveedor.emailPrincipal;
                    this.showItem.telefonoPrincipal = proveedor.telefonoPrincipal;
                    this.showItem.representante = proveedor.representante;
                    this.showItem.emailRepresentante = proveedor.emailRepresentante;
                    this.showItem.telefonoRepresentante = proveedor.telefonoRepresentante;
                    this.showItem.estado = this.estados.find(estado => estado.id == proveedor.estado).descripcion;
                    this.showItem.id = proveedor.id;

                    //dar click al boton de modal
                    document.getElementById('showProveedorModalBtn').click();

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
                        url: '/proveedores/delete/' + this.deleteItem.id,
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
                                title: 'Proveedor eliminado',
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
                            text: 'Ha ocurrido un error al eliminar el proveedor',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //Habilitar boton
                        document.getElementById('deleteButton').disabled = false;
                        document.getElementById('canceldeleteButton').disabled = false;

                        //Quitar icono de boton
                        document.getElementById('deleteButton').innerHTML =
                            'Eliminar';

                        //limpiar
                        this.cleanForm();
                        //Recargar proveedores
                        this.getAllProveedores();
                    })


                },
                DeleteProveedor(proveedor) {
                    this.deleteItem.nombre = proveedor.nombre;

                    this.deleteItem.id = proveedor.id;

                    //dar click al boton de modal
                    document.getElementById('deleteProveedorModalBtn').click();
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

                    if (!this.item.direccion) {
                        this.errors.direccion = 'Este campo es obligatorio';
                        document.getElementById('direccion').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('direccion').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.NIT) {
                        this.errors.NIT = 'Este campo es obligatorio';
                        document.getElementById('NIT').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('NIT').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.emailPrincipal) {
                        this.errors.emailPrincipal = 'Este campo es obligatorio';
                        document.getElementById('emailPrincipal').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('emailPrincipal').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.telefonoPrincipal) {
                        this.errors.telefonoPrincipal = 'Este campo es obligatorio';
                        document.getElementById('telefonoPrincipal').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('telefonoPrincipal').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.representante) {
                        this.errors.representante = 'Este campo es obligatorio';
                        document.getElementById('representante').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('representante').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.item.emailRepresentante) {
                        this.errors.emailRepresentante = 'Este campo es obligatorio';
                        document.getElementById('emailRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('emailRepresentante').setAttribute('class',
                            'form-control is-valid');
                    }

                    if (!this.item.telefonoRepresentante) {
                        this.errors.telefonoRepresentante = 'Este campo es obligatorio';
                        document.getElementById('telefonoRepresentante').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('telefonoRepresentante').setAttribute('class',
                            'form-control is-valid');
                    }


                    this.validateProveedorname();
                },
                validateEditForm() {

                    this.editErrors = {};

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.direccion) {
                        this.editErrors.direccion = 'Este campo es obligatorio';
                        document.getElementById('direccionEdit').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('direccionEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.NIT) {
                        this.editErrors.NIT = 'Este campo es obligatorio';
                        document.getElementById('NITEDIT').setAttribute('class', 'form-control is-invalid');
                    } else {
                        document.getElementById('NITEDIT').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.emailPrincipal) {
                        this.editErrors.emailPrincipal = 'Este campo es obligatorio';
                        document.getElementById('emailPrincipalEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('emailPrincipalEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                    if (!this.editItem.telefonoPrincipal) {
                        this.editErrors.telefonoPrincipal = 'Este campo es obligatorio';
                        document.getElementById('telefonoPrincipalEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('telefonoPrincipalEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                    if (!this.editItem.representante) {
                        this.editErrors.representante = 'Este campo es obligatorio';
                        document.getElementById('representanteEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('representanteEdit').setAttribute('class', 'form-control is-valid');
                    }

                    if (!this.editItem.emailRepresentante) {
                        this.editErrors.emailRepresentante = 'Este campo es obligatorio';
                        document.getElementById('emailRepresentanteEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('emailRepresentanteEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                    if (!this.editItem.telefonoRepresentante) {
                        this.editErrors.telefonoRepresentante = 'Este campo es obligatorio';
                        document.getElementById('telefonoRepresentanteEdit').setAttribute('class',
                            'form-control is-invalid');
                    } else {
                        document.getElementById('telefonoRepresentanteEdit').setAttribute('class',
                            'form-control is-valid');
                    }

                    document.getElementById('estadoEdit').setAttribute('class', 'form-control is-valid');

                    this.validateEditProveedorname();
                },
                validateProveedorname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9.]{5,}$/;

                    if (!regex.test(this.item.nombre)) {
                        document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                        this.errors.nombre =
                            'El proveedor debe tener al menos 5 caracteres y no contener caracteres especiales';
                    }

                    for (let i = 0; i < this.proveedores.length; i++) {
                        if (this.proveedores[i].nombre == this.item.nombre) {
                            document.getElementById('nombre').setAttribute('class', 'form-control is-invalid');
                            this.errors.nombre = 'El proveedor ya existe';
                        }
                    }
                },
                validateEditProveedorname() {

                    //Al menos 5 caracteres, con espacios y puntos  .!@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9.]{5,}$/;

                    if (!regex.test(this.editItem.nombre)) {
                        document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                        this.editErrors.nombre =
                            'El proveedor debe tener al menos 5 caracteres y no contener caracteres especiales';
                    }

                    //Eliminar del array el proveedor que se esta editando
                    this.proveedores = this.proveedores.filter(proveedor => proveedor.id != this.editItem.id);

                    //recorrer this.proveedores
                    for (let i = 0; i < this.proveedores.length; i++) {
                        if (this.proveedores[i].nombre == this.editItem.nombre) {
                            document.getElementById('nombreEdit').setAttribute('class', 'form-control is-invalid');
                            this.editErrors.nombre = 'El proveedor ya existe';
                        }
                    }

                },
                //Paginacion
                pageMinus() {
                    if (this.page > 1) {
                        this.page--;
                        this.getAllProveedores();
                    }
                },
                pagePlus() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.getAllProveedores();
                    }
                },
                specificPage(page) {
                    this.page = page;
                    this.getAllProveedores();
                },
                changePerPage() {
                    this.page = 1;
                    this.getAllProveedores();
                },
                //Limpiar formulario y busqueda
                cleanForm() {

                    this.item = {
                        nombre: '',
                        direccion: '',
                        NIT: '',
                        emailPrincipal: '',
                        emailRepresentante: '',
                        representante: '',
                        telefonoPrincipal: '',
                        telefonoRepresentante: '',
                    };

                    this.errors = {};
                    this.editErrors = {};
                    this.search = '';
                    //this.proveedores = [];
                    this.editItem = {
                        id: '',
                        nombre: '',
                        direccion: '',
                        NIT: '',
                        emailPrincipal: '',
                        emailRepresentante: '',
                        representante: '',
                        telefonoPrincipal: '',
                        telefonoRepresentante: '',
                        estado: '',
                    };
                    this.deleteItem = {
                        id: '',
                        nombre: '',
                        direccion: '',
                        NIT: '',
                        emailPrincipal: '',
                        emailRepresentante: '',
                        representante: '',
                        telefonoPrincipal: '',
                        telefonoRepresentante: '',
                        estado: '',
                    };

                    this.showItem = {
                        id: '',
                        nombre: '',
                        direccion: '',
                        NIT: '',
                        emailPrincipal: '',
                        emailRepresentante: '',
                        representante: '',
                        telefonoPrincipal: '',
                        telefonoRepresentante: '',
                        estado: '',
                    };

                    document.getElementById('nombre').setAttribute('class', 'form-control');
                    document.getElementById('direccion').setAttribute('class', 'form-control');
                    document.getElementById('NIT').setAttribute('class', 'form-control');
                    document.getElementById('emailPrincipal').setAttribute('class', 'form-control');
                    document.getElementById('telefonoPrincipal').setAttribute('class', 'form-control');
                    document.getElementById('representante').setAttribute('class', 'form-control');
                    document.getElementById('emailRepresentante').setAttribute('class', 'form-control');
                    document.getElementById('telefonoRepresentante').setAttribute('class', 'form-control');

                    document.getElementById('nombreEdit').setAttribute('class', 'form-control');
                    document.getElementById('direccionEdit').setAttribute('class', 'form-control');
                    document.getElementById('NITEDIT').setAttribute('class', 'form-control');
                    document.getElementById('emailPrincipalEdit').setAttribute('class', 'form-control');
                    document.getElementById('telefonoPrincipalEdit').setAttribute('class', 'form-control');
                    document.getElementById('representanteEdit').setAttribute('class', 'form-control');
                    document.getElementById('emailRepresentanteEdit').setAttribute('class', 'form-control');
                    document.getElementById('telefonoRepresentanteEdit').setAttribute('class', 'form-control');
                    document.getElementById('estadoEdit').setAttribute('class', 'form-control');

                    this.proveedores = this.searchProveedores;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.getAllProveedores();
                },
                //Obtener recursos
                async getAllProveedores() {

                    this.loading = true;
                    this.errors = {};
                    this.editErrors = {};

                    axios({
                        method: 'get',
                        url: '/allProveedores',
                        params: {
                            page: this.page,
                            per_page: this.per_page,
                            search: this.search
                        }
                    }).then(response => {
                        this.loading = false;
                        this.proveedores = response.data.data;
                        this.searchProveedores = response.data.data;

                        this.total = response.data.total;
                        this.totalPages = response.data.last_page;
                        if (this.page > this.totalPages) {
                            this.page = 1;
                            this.getAllProveedores();
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
                            text: 'Ha ocurrido un error al obtener los proveedores',
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
                this.getAllProveedores();

            }
        });
    </script>
@endsection
