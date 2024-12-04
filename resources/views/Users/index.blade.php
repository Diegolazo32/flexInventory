@extends('layouts.Navigation')

@section('title', 'Usuarios')

@section('content')
    <div class="full" id="app">
        <div class="card">
            <div class="card-header">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-10">
                        <h1>Usuarios</h1>
                    </div>

                    <div class="col-md-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUserModal"
                            style="height: 40px;">
                            Crear usuario
                        </button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="editUserModalBtn"
                            data-bs-target="#editUserModal" style="height: 40px;" hidden>
                            Crear usuario
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <form action="{{ route('users') }}" method="GET">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" class="form-control" name="search"
                                        placeholder="Buscar por nombre">
                                </div>
                                <div class="col-6" style="display: flex; justify-content: start; gap: 5px;">
                                    <button class="btn btn-primary">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped  table-hover" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">DUI</th>
                                    <th scope="col">Fecha de nacimiento</th>
                                    <th scope="col">Edad</th>
                                    <th scope="col">Genero</th>
                                    <th scope="col">Fecha de creacion</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Contraseña</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->nombre }}</td>
                                        <td>{{ $user->apellido }}</td>
                                        <td>
                                            @if ($user->DUI)
                                                {{ $user->DUI }}
                                            @else
                                                --
                                            @endif
                                        <td>
                                            {{ \Carbon\Carbon::parse($user->fechaNacimiento)->format('d/m/Y') }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($user->fechaNacimiento)->age }}</td>
                                        <td>
                                            @if ($user->genero == 1)
                                                Masculino
                                            @else
                                                Femenino
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                        <td>{{ $user->usuario }}</td>
                                        <td>
                                            @if ($user->password == null)
                                                <span class="badge bg-danger">No definida</span>
                                            @else
                                                <span class="badge bg-success">Definida</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->rol == 1)
                                                <span class="badge bg-primary">Administrador</span>
                                            @else
                                                <span class="badge bg-secondary">Usuario</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->estado == 1)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" @click="editUser({{ $user }})"><i
                                                    class="fas fa-pencil"></i></button>
                                            <!--<button class="btn btn-success"><i class="fas fa-toggle-on"></i></button>-->
                                            <button class="btn btn-warning"><i class="fas fa-lock-open"></i></button>
                                            @if ($user->estado == 1)
                                                <button class="btn btn-danger"><i class="fas fa-ban"></i></button>
                                            @else
                                                <button class="btn btn-success"><i class="fas fa-check"></i></button>
                                            @endif
                                            <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="12">
                                        {{ $users->links('pagination::bootstrap-4') }}
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="crearUserModal" tabindex="-1" aria-labelledby="crearUserModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false" style=" padding:200px;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="crearUserModalLabel">Crear usuario </h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="form" action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateForm" v-model="nombre"
                                            value="{{ old('nombre') }}">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger" v-if="errors.nombre">@{{ errors.nombre }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="apellido" name="apellido"
                                            value="{{ old('apellido') }}" placeholder="Apellido" v-model="apellido"
                                            @blur="validateForm">
                                        <label for="floatingInput">Apellido*</label>
                                        <small class="text-danger" v-if="errors.apellido">@{{ errors.apellido }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="DUI" name="DUI"
                                            value="{{ old('DUI') }}" placeholder="DUI" @blur="validateForm"
                                            v-model="DUI">
                                        <label for="floatingInput">DUI*</label>
                                        <small class="text-danger" v-if="errors.DUI">@{{ errors.DUI }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="fechaNacimiento"
                                            name="fechaNacimiento" placeholder="Fecha de nacimiento"
                                            value="{{ old('fechaNacimiento') }}" @change="validateDate"
                                            v-model="fechaNacimiento">
                                        <label for="floatingInput">Fecha de nacimiento*</label>
                                        <small class="text-danger"
                                            v-if="errors.fechaNacimiento">@{{ errors.fechaNacimiento }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="genero" name="genero" v-model="genero"
                                            @change="validateForm">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                        <label for="floatingInput">Genero*</label>
                                        <small class="text-danger" v-if="errors.genero">@{{ errors.genero }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="usuario" name="usuario"
                                            value="{{ old('usuario') }}" placeholder="Usuario" v-model="usuario"
                                            @blur="validateForm" @keyup="validateForm">
                                        <label for="floatingInput">Usuario*</label>
                                        <small class="text-danger" v-if="errors.usuario">@{{ errors.usuario }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="rol" name="rol" v-model="rol"
                                            @change="validateForm">
                                            <option value="1">Administrador</option>
                                            <option value="2">Usuario</option>
                                        </select>
                                        <label for="floatingInput">Rol*</label>
                                        <small class="text-danger" v-if="errors.rol">@{{ errors.rol }}</small>
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
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style=" padding:200px;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <h1 class="modal-title fs-5" id="editUserModalLabel">Editar usuario</h1>
                        <small class="text-muted"> Los campos marcados con * son obligatorios</small>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                    </div>
                    <div class="modal-body" style="padding: 25px;">
                        <form ref="formEdit">
                            @csrf
                            <div class="row">
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Nombre" @blur="validateEditForm" v-model="editItem.nombre"
                                            value="{{ old('nombre') }}">
                                        <label for="floatingInput">Nombre*</label>
                                        <small class="text-danger" v-if="editErrors.nombre">@{{ editErrors.nombre }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="apellido" name="apellido"
                                            value="{{ old('apellido') }}" placeholder="Apellido"
                                            v-model="editItem.apellido" @blur="validateEditForm">
                                        <label for="floatingInput">Apellido*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.apellido">@{{ editErrors.apellido }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="DUI" name="DUI"
                                            value="{{ old('DUI') }}" placeholder="DUI" @blur="validateEditForm"
                                            v-model="editItem.DUI">
                                        <label for="floatingInput">DUI*</label>
                                        <small class="text-danger" v-if="editErrors.DUI">@{{ editErrors.DUI }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" id="fechaNacimiento"
                                            name="fechaNacimiento" placeholder="Fecha de nacimiento"
                                            value="{{ old('fechaNacimiento') }}" @change="validateDate"
                                            v-model="editItem.fechaNacimiento">
                                        <label for="floatingInput">Fecha de nacimiento*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.fechaNacimiento">@{{ editErrors.fechaNacimiento }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="genero" name="genero"
                                            v-model="editItem.genero" @change="validateEditForm">
                                            <option value="1">Masculino</option>
                                            <option value="2">Femenino</option>
                                        </select>
                                        <label for="floatingInput">Genero*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.genero">@{{ editErrors.genero }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="usuario" name="usuario"
                                            value="{{ old('usuario') }}" placeholder="Usuario"
                                            v-model="editItem.usuario" @blur="validateEditForm"
                                            @keyup="validateEditForm">
                                        <label for="floatingInput">Usuario*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.usuario">@{{ editErrors.usuario }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="rol" name="rol" v-model="editItem.rol"
                                            @change="validateEditForm">
                                            <option value="1">Administrador</option>
                                            <option value="2">Usuario</option>
                                        </select>
                                        <label for="floatingInput">Rol*</label>
                                        <small class="text-danger" v-if="editErrors.rol">@{{ editErrors.rol }}</small>
                                    </div>
                                </div>
                                <div class="form-floating col-md-6" style="margin-bottom: 10px;">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="estado" name="estado"
                                            v-model="editItem.estado" @change="validateEditForm">
                                            <option value="1">Activo</option>
                                            <option value="2">Inactivo</option>
                                        </select>
                                        <label for="floatingInput">Estado*</label>
                                        <small class="text-danger"
                                            v-if="editErrors.estado">@{{ editErrors.estado }}</small>
                                    </div>
                                </div>
                                <!-- switch de estado -->

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButtonEdit"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="SubmitForm"
                            @click="sendFormEdit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                nombre: '',
                apellido: '',
                DUI: '',
                fechaNacimiento: '',
                genero: '',
                usuario: '',
                rol: '',
                errors: {},
                editErrors: {},
                editItem: {
                    id: '',
                    nombre: '',
                    apellido: '',
                    DUI: '',
                    fechaNacimiento: '',
                    genero: '',
                    usuario: '',
                    rol: '',
                    estado: ''
                },
            },
            methods: {
                validateForm() {
                    this.errors = {};

                    if (!this.nombre) {
                        this.errors.nombre = 'Este campo es obligatorio';
                    }
                    if (!this.genero) {
                        this.errors.genero = 'Este campo es obligatorio';
                    }
                    if (!this.usuario) {
                        this.errors.usuario = 'Este campo es obligatorio';
                    }
                    if (!this.rol) {
                        this.errors.rol = 'Este campo es obligatorio';
                    }
                    if (!this.fechaNacimiento) {
                        this.errors.fechaNacimiento = 'Este campo es obligatorio';
                    }
                    if (!this.apellido) {
                        this.errors.apellido = 'Este campo es obligatorio';
                    }
                    if (!this.DUI) {
                        this.errors.DUI = 'Este campo es obligatorio';
                    }

                    this.validateDate();
                    this.validateUsername();
                },
                validateEditForm() {

                    editErrors = {};

                    console.log(this.editItem.nombre);

                    if (!this.editItem.nombre) {
                        this.editErrors.nombre = 'Este campo es obligatorio';
                        document.getElementById('nombre').style.border = '1px solid red';
                    }
                    if (!this.editItem.genero) {
                        this.editErrors.genero = 'Este campo es obligatorio';

                    }
                    if (!this.editItem.usuario) {
                        this.editErrors.usuario = 'Este campo es obligatorio';
                    }
                    if (!this.editItem.rol) {
                        this.editErrors.rol = 'Este campo es obligatorio';
                    }
                    if (!this.editItem.fechaNacimiento) {
                        this.editErrors.fechaNacimiento = 'Este campo es obligatorio';
                    }
                    if (!this.editItem.apellido) {
                        this.editErrors.apellido = 'Este campo es obligatorio';
                    }
                    if (!this.editItem.DUI) {
                        this.editErrors.DUI = 'Este campo es obligatorio';
                    }

                    //Si el estado es activo se le asigna borde verde al campo
                    if (this.editItem.estado == 1) {
                        document.getElementById('estado').style.border = '1px solid green';
                    } else {
                        document.getElementById('estado').style.border = '1px solid red';
                    }


                    this.validateEditDate();
                    this.validateEditUsername();

                },
                sendForm() {

                    this.validateForm();

                    if (Object.keys(this.errors).length === 0) {
                        this.$refs.form.submit();

                        //disable button
                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButton').disabled = true;


                    }
                },
                sendFormEdit() {
                    this.validateEditForm();

                    if (Object.keys(this.editErrors).length === 0) {

                        axios.post('/users/update/' + this.editItem.id, this.editItem)
                            .then(response => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Usuario actualizado',
                                    showConfirmButton: false,
                                    timer: 1500
                                });


                                window.location.reload();


                            })
                            .catch(error => {
                                console.log(error);
                            });

                        //disable button
                        document.getElementById('SubmitForm').disabled = true;
                        document.getElementById('cancelButtonEdit').disabled = true;
                    }
                },
                cleanForm() {
                    this.nombre = '';
                    this.apellido = '';
                    this.DUI = '';
                    this.fechaNacimiento = '';
                    this.genero = '';
                    this.usuario = '';
                    this.rol = '';
                    this.errors = {};
                    this.editErrors = {};
                    this.usuarios = [];

                    this.getAllUsers();
                },
                validateDate() {
                    let date = new Date(this.fechaNacimiento);
                    let today = new Date();

                    if (date > today) {
                        this.errors.fechaNacimiento = 'La fecha de nacimiento no puede ser mayor a la fecha actual';
                    } else {
                        if (today.getFullYear() - date.getFullYear() < 18) {
                            this.errors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        }
                    }
                },
                validateEditDate() {
                    let date = new Date(this.editItem.fechaNacimiento);
                    let today = new Date();

                    if (date > today) {
                        this.editErrors.fechaNacimiento =
                            'La fecha de nacimiento no puede ser mayor a la fecha actual';
                    } else {
                        if (today.getFullYear() - date.getFullYear() < 18) {
                            this.editErrors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        }
                    }
                },
                validateUsername() {
                    //Al menos 5 caracteres y sin espacios o caracteres especiales, !@#$%^&*()_+
                    let regex = /^[a-zA-Z0-9]{5,}$/;

                    if (!regex.test(this.usuario)) {
                        this.errors.usuario =
                            'El usuario debe tener al menos 5 caracteres y no contener espacios o caracteres especiales';
                    }

                    for (let i = 0; i < this.usuarios.length; i++) {
                        if (this.usuarios[i].usuario == this.usuario) {
                            this.errors.usuario = 'El usuario ya existe';
                        }
                    }


                },
                validateEditUsername() {

                    this.editErrors = {};

                    let regex = /^[a-zA-Z0-9]{5,}$/;

                    if (!regex.test(this.editItem.usuario)) {
                        this.editErrors.usuario =
                            'El usuario debe tener al menos 5 caracteres y no contener espacios o caracteres especiales';
                    }

                    //Eliminar del array el usuario que se esta editando
                    this.usuarios = this.usuarios.filter(user => user.id != this.editItem.id);

                    //recorrer this.usuarios
                    for (let i = 0; i < this.usuarios.length; i++) {
                        if (this.usuarios[i].usuario == this.editItem.usuario) {
                            this.editErrors.usuario = 'El usuario ya existe';
                        }
                    }

                },
                editUser(user) {
                    this.editItem.nombre = user.nombre;
                    this.editItem.apellido = user.apellido;
                    this.editItem.DUI = user.DUI;
                    this.editItem.fechaNacimiento = user.fechaNacimiento;
                    this.editItem.genero = user.genero;
                    this.editItem.usuario = user.usuario;
                    this.editItem.rol = user.rol;
                    this.editItem.estado = user.estado;
                    this.editItem.id = user.id;

                    //dar click al boton de modal
                    document.getElementById('editUserModalBtn').click();

                },
                async getAllUsers() {
                    let response = await fetch('/allUsers');
                    let data = await response.json();
                    //Maps usuario y id
                    this.usuarios = data;

                }
            },
            mounted() {
                this.getAllUsers();
            }
        });
    </script>
@endsection
