@extends('layouts.layout')

@section('content')

    <body id="app" class="container BgLogin">

        <div class="row">

            <div class="col-md-12 LoginPosition">

                <div class="card LoginForm">

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card-body" style="display: flex; justify-content: center; flex-direction: column;">

                        <h1>Bienvenido</h1>

                        <form action="{{ route('AuthLogin') }}" method="post">
                            @csrf
                            <label for="usuario">Usuario</label>
                            <input type="text" name="usuario" class="form-control" required>
                            <small error="text-danger">{{ $errors->first('usuario') }}</small>
                            <br>
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                            <small error="text-danger">{{ $errors->first('password') }}</small>
                            <br>
                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                        </form>
                    </div>

                </div>

                <div class="errorMessage" style="margin-top:5px; width: 100%; max-width:400px;">
                    @if (session('message'))
                        <div class="alert alert-danger">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </body>


    <script></script>
@endsection
