@extends('layouts.Navigation')

@section('title', 'Usuarios')

@section('content')
    <div class="full">
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
                                    <button class="btn btn-primary">Limpiar</button>
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
                                        <td>{{ $user->DUI }}</td>
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
                                            <button class="btn btn-primary"><i class="fas fa-pencil"></i></button>
                                            <!--<button class="btn btn-success"><i class="fas fa-toggle-on"></i></button>-->
                                            <button class="btn btn-warning"><i class="fas fa-lock-open"></i></button>
                                            @if ($user->estado == 1)
                                                <button class="btn btn-danger"><i class="fas fa-ban"></i></button>
                                            @else
                                                <button class="btn btn-success"><i class="fas fa-lock-check"></i></button>
                                            @endif
                                            <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                {{ $users->links() }}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="crearUserModal" tabindex="-1" aria-labelledby="crearUserModalLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="crearUserModalLabel">Crear usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form ref="form" action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre*</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        v-model="nombre" @blur="validateForm">
                                    <small v-if="errors.nombre" class="form-text text-danger">@{{ errors.nombre }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido*</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido"
                                        v-model="apellido" @blur="validateForm">
                                    <small v-if="errors.apellido"
                                        class="form-text text-danger">@{{ errors.apellido }}</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="DUI" class="form-label">DUI*</label>
                                    <input type="text" class="form-control" id="DUI" name="DUI"
                                        v-model="DUI" @blur="validateForm">
                                    <small v-if="errors.DUI" class="form-text text-danger">@{{ errors.DUI }}</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="fechaNacimiento" class="form-label">Fecha de nacimiento*</label>
                                    <input type="date" class="form-control" id="fechaNacimiento"
                                        name="fechaNacimiento" v-model="fechaNacimiento" @blur="validateForm"
                                        @change="validateDate">
                                    <small v-if="errors.fechaNacimiento"
                                        class="form-text text-danger">@{{ errors.fechaNacimiento }}</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="genero" class="form-label">Genero*</label>
                                    <select class="form-select" id="genero" name="genero" v-model="genero"
                                        @change="validateForm">
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                    <small v-if="errors.genero"
                                        class="form-text text-danger">@{{ errors.genero }}</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="usuario" class="form-label">Usuario*</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario"
                                        v-model="usuario" @blur="validateForm">
                                    <small v-if="errors.usuario"
                                        class="form-text text-danger">@{{ errors.usuario }}</small>
                                </div>
                                <div class="col-md-4">
                                    <label for="rol" class="form-label">Rol*</label>
                                    <select class="form-select" id="rol" name="rol" v-model="rol"
                                        @change="validateForm">
                                        <option value="1">Administrador</option>
                                        <option value="2">Usuario</option>
                                    </select>
                                    <small v-if="errors.rol" class="form-text text-danger">@{{ errors.rol }}</small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            @click="cleanForm">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="validateForm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#crearUserModal',
            data: {
                nombre: '',
                apellido: '',
                DUI: '',
                fechaNacimiento: '',
                genero: '',
                usuario: '',
                rol: '',
                errors: {}
            },
            methods: {
                validateForm() {
                    this.errors = {};

                    if (!this.nombre) {
                        this.errors.nombre = 'Este campo es obligatorio';
                    }
                    if (!this.apellido) {
                        this.errors.apellido = 'Este campo es obligatorio';
                    }
                    if (!this.DUI) {
                        this.errors.DUI = 'Este campo es obligatorio';
                    }
                    if (!this.fechaNacimiento) {
                        this.errors.fechaNacimiento = 'Este campo es obligatorio';

                    } else {
                        this.validateDate();
                    }
                    if (!this.genero) {
                        this.errors.genero = 'Este campo es obligatorio';
                    }
                    if (!this.usuario) {
                        this.errors.usuario = 'Este campo es obligatorio';
                    } else {
                        this.validateUsername();
                    }
                    if (!this.rol) {
                        this.errors.rol = 'Este campo es obligatorio';
                    }

                    if (Object.keys(this.errors).length === 0) {
                        this.$refs.form.submit();
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
                },
                validateDate() {
                    let date = new Date(this.fechaNacimiento);
                    let today = new Date();
                    this.errors.fechaNacimiento = '';

                    if (date > today) {
                        this.errors.fechaNacimiento = 'La fecha de nacimiento no puede ser mayor a la fecha actual';
                    } else {
                        if (today.getFullYear() - date.getFullYear() < 18) {
                            this.errors.fechaNacimiento = 'El usuario debe ser mayor de edad';
                        } else {
                            this.errors.fechaNacimiento = '';
                        }
                    }
                },
                validateUsername() {
                    //Al menos 6 caracteres y sin espacios o caracteres especiales
                    let regex = /^[a-zA-Z0-9]{6,}$/;
                    if (!regex.test(this.usuario)) {
                        this.errors.usuario =
                            'El usuario debe tener al menos 6 caracteres y no debe contener espacios o caracteres especiales';
                    } else {
                        this.errors.usuario = '';
                    }
                }
            }
        });
    </script>
@endsection
