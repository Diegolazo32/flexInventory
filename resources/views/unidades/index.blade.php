@extends('layouts.Navigation')

@section('title', 'Unidades')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-10">
                        <h1>Unidades</h1>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-md-2 d-flex justify-content-end">
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
                    <div class="col-md-10">

                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" name="search"
                                    placeholder="Buscar por descripcion o abreviatura" v-model="search">
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
            <!-- Tabla de unidades -->
            <div class="row">
                <div class="card-body">

                    <div v-if="unidades.length == 0 && searchUnidades == 0" role="alert"
                        style="display:block; margin-left: 50%;" id="loading">
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
        <div class="modal fade" id="crearUnidadModal" tabindex="-1" aria-labelledby="crearUnidadModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearUnidadModalLabel">Crear unidad </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('unidades.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            placeholder="Descripcion" @blur="validateForm" v-model="item.descripcion"
                                            value="{{ old('descripcion') }}">
                                        <label for="floatingInput">Descripcion*</label>
                                        <small class="text-danger"
                                            v-if="errors.descripcion">@{{ errors.descripcion }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="abreviatura" name="abreviatura"
                                            value="{{ old('abreviatura') }}" placeholder="Abreviatura"
                                            v-model="item.abreviatura" @blur="validateForm">
                                        <label for="floatingInput">Abreviatura*</label>
                                        <small class="text-danger"
                                            v-if="errors.abreviatura">@{{ errors.abreviatura }}</small>
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
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editUnidadModalLabel">Editar unidad</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">

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
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">

                                        <!-- Abreviatura -->
                                        <input type="text" class="form-control" id="abreviaturaEdit"
                                            name="abreviatura" placeholder="Abreviatura" v-model="editItem.abreviatura"
                                            @blur="validateEditForm">
                                        <label for="floatingInput">Abreviatura*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.abreviatura">@{{ editErrors.abreviatura }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-4" style="margin-bottom: 10px;">
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
        <div class="modal fade" id="deleteUnidadModal" tabindex="-1" aria-labelledby="deleteUnidadModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
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
                searchError: '',
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

                            //Quitar icono de boton
                            document.getElementById('SubmitForm').innerHTML =
                                '<i class="fas fa-save"></i>';

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
                        document.getElementById('descripcion').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcion').style.border = '1px solid green';
                    }
                    if (!this.item.abreviatura) {
                        this.errors.abreviatura = 'Este campo es obligatorio';
                        document.getElementById('abreviatura').style.border = '1px solid red';
                    } else {
                        document.getElementById('abreviatura').style.border = '1px solid green';
                    }

                    this.validateUnidadname();
                },
                validateEditForm() {

                    editErrors = {};

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcionEdit').style.border = '1px solid green';
                    }

                    if (!this.editItem.abreviatura) {
                        this.editErrors.abreviatura = 'Este campo es obligatorio';
                        document.getElementById('abreviaturaEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('abreviaturaEdit').style.border = '1px solid green';
                    }

                    document.getElementById('estadoEdit').style.border = '1px solid green';

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
                    let unidades = this.searchUnidades;

                    try {
                        this.filtered = unidades.filter(unidad => {
                            return unidad.descripcion.toLowerCase().includes(search) ||
                                unidad.abreviatura.toLowerCase().includes(search)
                        });
                    } catch (error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar la unidad',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }

                    if (this.filtered.length == 0) {
                        this.searchError = 'No se encontraron resultados';
                    }

                    this.unidades = this.filtered;
                },
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

                    document.getElementById('descripcion').style.border = '1px solid #ced4da';
                    document.getElementById('abreviatura').style.border = '1px solid #ced4da';

                    document.getElementById('descripcionEdit').style.border = '1px solid #ced4da';
                    document.getElementById('abreviaturaEdit').style.border = '1px solid #ced4da';
                    document.getElementById('estadoEdit').style.border = '1px solid #ced4da';

                    this.unidades = this.searchUnidades;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.unidades = this.searchUnidades;
                },
                //Obtener recursos
                async getAllUnidades() {

                    try {
                        let response = await fetch('/allUnidades');
                        let data = await response.json();

                        if (data.length == 0) {
                            document.getElementById('loading').style.display = 'block';
                            document.getElementById('loading').innerHTML = 'No hay unidades registradas';
                        } else {
                            this.unidades = data;
                            this.searchUnidades = data;
                        }
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

            },
            mounted() {
                this.getAllEstados();
                this.getAllUnidades();

            }
        });
    </script>
@endsection
