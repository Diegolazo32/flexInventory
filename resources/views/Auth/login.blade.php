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
        <div id="LoginApp">
            <div class="card hoverCard LoginForm" style="width: 350px;">
                <div class="card-body" style="display: flex; justify-content: center; flex-direction: column;">
                    <div class="card-title">
                        <h2 class="text-center">@{{ mensaje }}</h2>
                    </div>
                    <form action="{{ route('AuthLogin') }}" method="post" ref="loginForm">
                        @csrf
                        <div class="form-floating  mb-3">
                            <input type="text" name="usuario" class="form-control" id="floatingUsuario"
                                placeholder="Usuario" required autocomplete="username">
                            <label for="floatingUsuario">Usuario</label>
                            <small error="text-danger">{{ $errors->first('usuario') }}</small>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="floatingPassword"
                                placeholder="Contraseña" required autocomplete="current-password">
                            <label for="floatingPassword">Contraseña</label>
                            <small error="text-danger">{{ $errors->first('password') }}</small>
                        </div>
                        <button type="button" id="LoginButton" class="btn btn-outline-primary" @click="LogIn">Iniciar
                            sesión</button>
                    </form>
                    <small @click="forgotPassword" class="text-muted" style="cursor: pointer; margin-top: 10px;">¿Ha
                        olvidado su
                        contraseña?</small>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    new Vue({
        el: '#LoginApp',
        data: {
            mensaje: 'Iniciar sesión',
        },
        methods: {
            LogIn() {

                //Cambiar icono de boton
                document.getElementById('LoginButton').innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';

                document.getElementById('LoginButton').disabled = true;

                this.$refs.loginForm.submit();
            },
            forgotPassword() {
                swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000,
                    title: '¿Olvidaste tu contraseña?',
                    text: 'Informa a tu administrador para que te ayude a recuperar tu contraseña',
                    icon: 'info',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        onMounted() {}
    });
</script>

</html>
