@extends('layouts.Navigation')

@section('title', 'Roles')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-10">
                        <h1>Roles</h1>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-md-2 d-flex justify-content-end">
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
                    <div class="col-md-10">

                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="search" placeholder="Buscar"
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
            <!-- Tabla de roles -->
            <div class="row">
                <div class="card-body">
                    <div class="table-responsive">

                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Descripcion</th>
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
                                        <span class="badge bg-success">@{{ estados.find(estado => estado.id == rol.estado).descripcion }}</span>
                                    </td>
                                    <td v-else>
                                        <span class="badge bg-danger">@{{ estados.find(estado => estado.id == rol.estado).descripcion }}</span>
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editRol(rol)">
                                            <i class="fas fa-pencil"></i>
                                        </button>
                                        <button id="permisosBtn" class="btn btn-warning" @click="getPermisos(rol)">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteRol(rol)">
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
        <div class="modal fade" id="crearRolModal" tabindex="-1" aria-labelledby="crearRolModalLabel" aria-hidden="inert"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearRolModalLabel">Crear rol </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('roles.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-12" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion"
                                            value="{{ old('descripcion') }}">
                                        <label for="floatingInput">Descripcion*</label>
                                        <small class="text-danger"
                                            v-if="errors.descripcion">@{{ errors.descripcion }}</small>
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
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editRolModalLabel">Editar rol</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">

                                    <div class="form-floating mb-3">
                                        <!-- Descripcion -->
                                        <input type="text" class="form-control" id="descripcionEdit"
                                            name="descripcion" placeholder="Descripcion" @blur="validateEditForm"
                                            v-model="editItem.descripcion">
                                        <label for="floatingInput">Descripcion*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.descripcion">@{{ editErrors.descripcion }}</small>
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
                                        <small class="text-danger" v-if="editErrors.estado">@{{ editErrors.estado }}</small>

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
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 1200px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="permisosModalLabel">Permisos</h1>
                        <small class="text-muted text-danger"> Permisos del rol: @{{ permisosItem.descripcion }}</small>
                    </div>
                    <div class="modal-body text-center" style="padding: 25px;">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Permisos</h3>
                                <div class="table-responsive">

                                    <!-- vue foreach agrupando los permisos por grupo -->


                                    <div class="card" v-for="grupo in grupos" :key="grupo.id">

                                        <div class="card-title">
                                            <h4>@{{ grupo.descripcion }}</h4>

                                            <button class="btn" type="button" data-bs-toggle="collapse"
                                                :data-bs-target="'#collapse' + grupo.id" aria-expanded="false"
                                                aria-controls="collapseExample">
                                                V
                                            </button>

                                        </div>


                                        <div class="collapse" :id="'collapse' + grupo.id">
                                            <div class="card-body">
                                                <table class="table table-striped  table-hover"
                                                    style="text-align: center;">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Descripcion</th>
                                                            <th scope="col">Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
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
                        <button type="button" class="btn btn-primary" id="SubmitPermisos"
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
                    estado: ''
                },
                deleteItem: {
                    id: '',
                    descripcion: '',
                    estado: ''
                },
                permisosItem: {
                    id: '',
                    descripcion: '',
                    permisos: [],
                    estado: '',
                },
                permisos: [],
                permisosRol: [],
                search: '',
                errors: {},
                editErrors: {},
                roles: [],
                searchRoles: [],
                filtered: [],
                estados: [],
                grupos: [],
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
                            url: '/roles/store',
                            data: this.item
                        }).then(response => {

                            //Habilitar boton
                            document.getElementById('SubmitForm').disabled = false;
                            document.getElementById('cancelButton').disabled = false;

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                '<i class="fas fa-save"></i>';

                            //Cerrar modal
                            document.getElementById('cancelButton').click();

                            if (response.data.success) {
                                swal.fire({
                                    title: 'Rol creado',
                                    text: 'El rol ha sido creado correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al crear la rol',
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
                                text: 'Ha ocurrido un error al crear la rol',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar roles
                            this.getAllRoles();
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
                            url: '/roles/edit/' + this.editItem.id,
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
                                    title: 'Rol editado',
                                    text: 'El rol ha sido editado correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al editar la rol',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }


                        }).catch(error => {

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al editar la rol',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar roles
                            this.getAllRoles();

                        })

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
                        '<i class="fas fa-spinner fa-spin"></i> Eliminando...';

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
                                title: 'Rol eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        }

                    }).catch(error => {
                        swal.fire({
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
                    this.permisosItem.permisos = this.permisosRol;

                    //dar click al boton de modal
                    document.getElementById('permisosModalBtn').click();
                },
                sendPermisosForm() {
                    //Logica de permisos xd
                },
                //Validaciones
                validateForm() {
                    this.errors = {};

                    if (!this.item.descripcion) {
                        this.errors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcion').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcion').style.border = '1px solid green';
                    }


                    this.validateRolname();
                },
                validateEditForm() {

                    editErrors = {};

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcionEdit').style.border = '1px solid green';
                    }

                    document.getElementById('estadoEdit').style.border = '1px solid green';

                    this.validateEditRolname();
                },
                validateRolname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.item.descripcion)) {
                        document.getElementById('descripcion').style.border = '1px solid #ced4da';
                        document.getElementById('descripcion').style.border = '1px solid red';
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
                        document.getElementById('descripcionEdit').style.border = '1px solid #ced4da';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
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
                //Limpiar formulario y busqueda
                searchFn() {
                    let search = this.search.toLowerCase();
                    let roles = this.searchRoles;

                    try {
                        this.filtered = roles.filter(rol => {
                            return rol.descripcion.toLowerCase().includes(search)
                        });
                    } catch (error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar la rol',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }

                    this.roles = this.filtered;
                },
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

                    document.getElementById('descripcion').style.border = '1px solid #ced4da';

                    document.getElementById('descripcionEdit').style.border = '1px solid #ced4da';
                    document.getElementById('estadoEdit').style.border = '1px solid #ced4da';

                    this.roles = this.searchRoles;
                },
                cleanSearch() {
                    this.search = '';
                    this.roles = this.searchRoles;
                },
                //Obtener recursos
                async getAllRoles() {
                    let response = await fetch('/allRoles');
                    let data = await response.json();
                    this.roles = data;
                    this.searchRoles = data;
                },
                async getAllEstados() {
                    let response = await fetch('/allEstados');
                    let data = await response.json();
                    this.estados = data;
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
                this.getAllRoles();
            }
        });
    </script>
@endsection
