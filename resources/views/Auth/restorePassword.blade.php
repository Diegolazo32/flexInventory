<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Iniciar sesion</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Vue.js -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/ddb0e8a634.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <!-- axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js">
        const axios = require('axios').default;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --login-bg: url('{{ asset('storage/' . \App\Models\empresa::first()->logo ?? 'default_bg.jpg') }}?t={{ time() }}');
        }
    </style>

    @vite('resources/css/app.css')


</head>

<body class="BgLogin"
    style="background-image: url('{{ asset('storage/' . \App\Models\empresa::first()->logo ?? 'default_bg.jpg') }}?t={{ time() }}'); background-size: cover; background-position: center;">

    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; width: auto;">
        <div class="row" id="ResetPassword">
            <div class="col-lg-12" style="display: flex; justify-content: center; align-items: center;">
                <div class="card hoverCard LoginForm">
                    <div class="card-body" style="display: flex; justify-content: center; flex-direction: column;">
                        <div class="card-title">
                            <h2 class="text-center"> Reestablecer contraseña </h2>
                        </div>
                        <form action="{{ route('updatePassword', ['id' => $usuario->id]) }}" method="post"
                            @submit.prevent="sendForm" ref="loginForm">
                            @csrf
                            <div class="form-floating  mb-3" style=" display: flex; flex-direction: column;">

                                <input type="password" name="password" class="form-control" id="floatingPassword"
                                    placeholder="Contraseña" v-model="password" required @keyup="validarPassword"
                                    autocomplete="current-password">
                                <label for="floatingPassword">Nueva contraseña</label>
                                <small class="text" id="minimolargo">• Minimo 4 caracteres</small>
                                <small class="text" id="minimonumero">• Al menos 1 numero</small>
                                <small class="text" id="minimoletra">• Al menos 1 letra</small>
                            </div>

                            <button type="button" id="updateBoton" class="btn btn-outline-primary"
                                @click="sendForm">Actualizar contraseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    new Vue({
        el: '#ResetPassword',
        data: {
            mensaje: 'Iniciar sesión',
            password: '',
            errors: {}
        },
        methods: {
            sendForm() {

                //Cambiar icono de boton
                document.getElementById('updateBoton').innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Actualizando contraseña...';

                this.validarPassword();

                if (this.errors.password) {
                    swal.fire({
                        toast: true,
                        showCloseButton: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        title: 'Error',
                        text: 'La contraseña no cumple con los requisitos',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    document.getElementById('updateBoton').disabled = false;
                    document.getElementById('updateBoton').innerHTML = 'Actualizar contraseña';

                    return;
                }

                document.getElementById('updateBoton').disabled = true;
                this.$refs.loginForm.submit();
            },
            validarPassword() {
                this.errors = {};

                if (this.password.length >= 4) {
                    document.getElementById('minimolargo').style.color = 'green';
                } else {
                    document.getElementById('minimolargo').style.color = 'red';
                    this.errors.password = 'La contraseña debe tener al menos 4 caracteres';
                }

                if (this.password.match(/[0-9]/)) {
                    document.getElementById('minimonumero').style.color = 'green';
                } else {
                    document.getElementById('minimonumero').style.color = 'red';
                    this.errors.password = 'La contraseña debe tener al menos un número';
                }

                if (this.password.match(/[a-z]/)) {
                    document.getElementById('minimoletra').style.color = 'green';
                } else {
                    document.getElementById('minimoletra').style.color = 'red';
                    this.errors.password = 'La contraseña debe tener al menos una letra';
                }

                if (this.password.length == 0) {
                    document.getElementById('minimolargo').style.color = 'red';
                    document.getElementById('minimonumero').style.color = 'red';
                    document.getElementById('minimoletra').style.color = 'red';
                    this.errors.password = 'La contraseña es requerida';
                }



            }
        },
        onMounted() {}
    });
</script>

</html>
