@extends('Layouts.layout')

@section('title', 'Restaurar contraseña')

@section('content')

<body class="container BgLogin" style="display: grid; place-items: center; height: 80vh;">
    <div class="row" id="ResetPassword">
        <div class="col-lg-12" style="display: flex; justify-content: center; align-items: center;">
            <div class="card hoverCard LoginForm">
                <div class="card-body" style="display: flex; justify-content: center; flex-direction: column;">
                    <div class="card-title">
                        <h2 class="text-center"> Reestablecer contraseña </h2>
                        <small class="text-center"> {{ Auth::user()->nombre }} </small>
                        <small class="text-center"> {{ Auth::user()->usuario }} </small>
                    </div>
                    <form action="{{ route('updatePassword', ['id' => Auth::user()->id]) }}" method="post"
                        ref="loginForm">
                        @csrf
                        <div class="form-floating  mb-3" style=" display: flex; flex-direction: column;">

                            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="usuario" value="{{ Auth::user()->usuario }}">

                            <input type="password" name="password" class="form-control" id="floatingPassword"
                                placeholder="Contraseña" v-model="password" required @keyup="validarPassword" autocomplete="current-password">
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
                document.getElementById('updateBoton').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando contraseña...';

                this.validarPassword();

                if (this.errors.password) {
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

            }
        },
        onMounted() {}
    });
</script>

@endsection
