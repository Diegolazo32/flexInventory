@extends('layouts.Navigation')

@section('title', 'Estados')

@section('content')
    <div id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-10">
                        <h1>Estados</h1>
                    </div>
                    <!-- Botones de accion -->
                    <div class="col-md-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#crearEstadoModal" style="height: 40px;">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editEstadoModalBtn"
                            data-bs-target="#editEstadoModal" style="height: 40px;" hidden>
                            Editar estado
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="deleteEstadoModalBtn"
                            data-bs-target="#deleteEstadoModal" style="height: 40px;" hidden>
                            Eliminar estado
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
                                    placeholder="Buscar por descripción" v-model="search">
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
            <!-- Tabla de estados -->
            <div class="row">
                <div class="card-body">

                    <div v-if="loading" role="alert" style="display:block; margin-left: 50%;" id="loading">
                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                    </div>

                    <div v-if="estados.error" class="alert alert-danger" role="alert">
                        <h3>@{{ estados.error }}</h3>
                    </div>

                    <div v-if="estados.length > 0" class="table-responsive">
                        <table ref="table" class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- vue foreach -->
                                <tr v-for='estado in estados' :key="estado.id">
                                    <td v-if="estado.descripcion.length > 15">
                                        @{{ estado.descripcion.substring(0, 15) }}...
                                    </td>
                                    <td v-else>
                                        @{{ estado.descripcion }}
                                    </td>
                                    <td>
                                        <button id="editBTN" class="btn btn-primary" @click="editEstado(estado)">
                                            <i class="fas fa-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger" id="dltBTN" @click="DeleteEstado(estado)">
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
        <div class="modal fade" id="crearEstadoModal" tabindex="-1" aria-labelledby="crearEstadoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearEstadoModalLabel">Crear estado </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('estados.store') }}" method="POST">
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
        <div class="modal fade" id="editEstadoModal" tabindex="-1" aria-labelledby="editEstadoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editEstadoModalLabel">Editar estado</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-12" style="margin-bottom: 10px;">
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
        <div class="modal fade" id="deleteEstadoModal" tabindex="-1" aria-labelledby="deleteEstadoModalLabel"
            aria-hidden="inert" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="deleteEstadoModalLabel">Eliminar estado</h1>
                        <small class="text-muted text-danger"> ¿Estas seguro de eliminar este estado?</small><br>
                        <small class="text-muted"> Esta accion no se puede deshacer</small><br>
                        <small class="text-muted"> Nota: No se pueden eliminar estados que esten en uso</small>
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
                    descripcion: '',
                },
                editItem: {
                    id: '',
                    descripcion: '',
                },
                deleteItem: {
                    id: '',
                    descripcion: '',
                },
                search: '',
                errors: {},
                editErrors: {},
                estados: [],
                searchEstados: [],
                filtered: [],
                estados: [],
                loading: true,
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
                            url: '/estados/store',
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
                                    title: 'Estado creada',
                                    text: 'El estado ha sido creada correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al crear la estado',
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
                                text: 'Ha ocurrido un error al crear la estado',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar estados
                            this.getAllEstados();
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
                            url: '/estados/edit/' + this.editItem.id,
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
                                    title: 'Estado editada',
                                    text: 'El estado ha sido editada correctamente',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: 'Ha ocurrido un error al editar la estado',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar'
                                });
                            }


                        }).catch(error => {

                            swal.fire({
                                title: 'Error',
                                text: 'Ha ocurrido un error al editar la estado',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });

                        }).finally(() => {

                            //limpiar
                            this.cleanForm();
                            //Recargar estados
                            this.getAllEstados();

                        })

                    }
                },
                editEstado(estado) {
                    this.editItem.descripcion = estado.descripcion;
                    this.editItem.estado = estado.estado;
                    this.editItem.id = estado.id;

                    //dar click al boton de modal
                    document.getElementById('editEstadoModalBtn').click();

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
                        url: '/estados/delete/' + this.deleteItem.id,
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
                                title: 'Estado eliminado',
                                text: response.data.success,
                                icon: 'success',
                                confirmButtonText: 'Aceptar',
                            });
                        }

                    }).catch(error => {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al eliminar la estado',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });

                    }).finally(() => {

                        //limpiar
                        this.cleanForm();
                        //Recargar estados
                        this.getAllEstados();
                    })


                },
                DeleteEstado(estado) {
                    this.deleteItem.descripcion = estado.descripcion;
                    this.deleteItem.id = estado.id;

                    //dar click al boton de modal
                    document.getElementById('deleteEstadoModalBtn').click();
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

                    this.validateEstadoname();

                },
                validateEditForm() {

                    editErrors = {};

                    if (!this.editItem.descripcion) {
                        this.editErrors.descripcion = 'Este campo es obligatorio';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
                    } else {
                        document.getElementById('descripcionEdit').style.border = '1px solid green';
                    }

                    this.validateEditEstadoname();

                },
                validateEstadoname() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[ a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.item.descripcion)) {
                        document.getElementById('descripcion').style.border = '1px solid #ced4da';
                        document.getElementById('descripcion').style.border = '1px solid red';
                        this.errors.descripcion =
                            'El estado debe tener al menos 3 caracteres y no contener caracteres especiales';
                    }

                    for (let i = 0; i < this.estados.length; i++) {
                        if (this.estados[i].descripcion == this.item.descripcion) {
                            this.errors.descripcion = 'El estado ya existe';
                        }
                    }
                },
                validateEditEstadoname() {

                    this.editErrors = {};

                    //Permitir espacios y letras, numeros no y minimo 3 caracteres
                    let regex = /^[ a-zA-Z0-9]{3,}$/;

                    if (!regex.test(this.editItem.descripcion)) {
                        document.getElementById('descripcionEdit').style.border = '1px solid #ced4da';
                        document.getElementById('descripcionEdit').style.border = '1px solid red';
                        this.editErrors.descripcion =
                            'El estado debe tener al menos 3 caracteres y no contener caracteres especiales';

                    }

                    //Eliminar del array la estado que se esta editando
                    this.estados = this.estados.filter(estado => estado.id != this.editItem.id);

                    //recorrer this.estados
                    for (let i = 0; i < this.estados.length; i++) {
                        if (this.estados[i].descripcion == this.editItem.descripcion) {
                            this.editErrors.descripcion = 'El estado ya existe';
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
                    let estados = this.searchEstados;

                    try {
                        this.filtered = estados.filter(estado => {
                            return estado.descripcion.toLowerCase().includes(search)

                        });
                    } catch (error) {
                        swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al buscar la estado',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }

                    if (this.filtered.length == 0) {
                        this.searchError = 'No se encontraron resultados';
                    }

                    this.estados = this.filtered;
                },
                cleanForm() {

                    this.item = {
                        descripcion: '',
                    };

                    this.errors = {};
                    this.editErrors = {};
                    this.search = '';
                    //this.estados = [];
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

                    this.estados = this.searchEstados;
                },
                cleanSearch() {
                    this.search = '';
                    this.searchError = '';
                    this.estados = this.searchEstados;
                },
                //Obtener recursos
                async getAllEstados() {

                    try {
                        let response = await fetch('/allEstados');
                        let data = await response.json();
                        this.loading = false;
                        this.estados = data;
                        this.searchEstados = data;

                        //console.log(this.estados);

                    } catch (error) {

                    }


                },

            },
            mounted() {
                this.getAllEstados();
            }
        });
    </script>
@endsection
