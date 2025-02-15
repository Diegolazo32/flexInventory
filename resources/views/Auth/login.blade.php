@extends('layouts.layout')

@section('content')

<body class="container BgLogin" style="display: grid; place-items: center; height: 80vh;">
    <div class="row" id="LoginApp">
        <div class="col-lg-12" style="display: flex; justify-content: center; align-items: center;">
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
                document.getElementById('LoginButton').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';

                document.getElementById('LoginButton').disabled = true;

                this.$refs.loginForm.submit();
            }
        },
        onMounted() {}
    });
</script>
@endsection
