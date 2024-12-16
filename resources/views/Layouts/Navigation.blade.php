<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

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

    @vite(['/resources/css/app.css'])
</head>

<body>
    <!-- container-fluid hace que el contenido se ajuste al ancho de la pantalla -->

    <!-- NavBar -->
    <nav class="navbar navbar-dark bg-dark" style="padding: 10px;">

        <div class="row d-flex align-items-center" style="width: 100%; padding: 5px;">
            <div class="col-10 d-flex justify-content-start">
                <!-- Logo circular -->
                <a href="{{ route('dashboard') }}" style="font-weight: normal">
                    <img src="{{ asset('storage/Bg.jpg') }}" alt="Logo" class="rounded-circle"
                        style="width: 50px; height: 50px;">
                </a>

                <a class="navbar-brand" href="{{ route('dashboard') }}"
                    style="margin-left:15px; font-weight: normal">Inventario Flexible</a>
            </div>
            <div class="col-2 d-flex justify-content-center">

                <div class="dropdown">
                    <button class="btn dropdown-toggle btn-outline-light" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ Auth::user()->nombre }}
                    </button>
                    <ul class="dropdown-menu ">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuracion</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesion</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>

    <!--SideBar-->
    <div class="container-fluid" style="height: 100%;">
        <div class="row" style="height: 100%;">
            <div class="col-2 bg-dark" style=" width: 100%; max-width: 200px;">
                <nav class="nav flex-column nav-pills" style="padding: 10px;">
                    <button class="btn btn-outline-light"
                        style="margin: 5px; display:flex; align-items: center; justify-content:center;"
                        onclick="window.location.href='{{ route('dashboard') }}'">
                        <i class="fa-solid fa-house"></i>
                        <span style="margin-left: 10px;">Inicio</span>
                    </button>

                    @if (Auth::user()->rol == 1)
                        <button class="btn btn-outline-light"
                            style="margin: 5px; display:flex; align-items: center; justify-content:center;"
                            onclick="window.location.href='{{ route('users') }}'">
                            <i class="fa-solid fa-user"></i>
                            <span style="margin-left: 10px;">Usuarios</span>
                        </button>
                        <button class="btn btn-outline-light"
                            style="margin: 5px; display:flex; align-items: center; justify-content:center;"
                            onclick="window.location.href='{{ route('unidades') }}'">
                            <i class="fa-solid fa-list"></i>
                            <span style="margin-left: 10px;">Unidades</span>
                        </button>
                        <button class="btn btn-outline-light"
                            style="margin: 5px; display:flex; align-items: center; justify-content:center;"
                            onclick="window.location.href='{{ route('estados') }}'">
                            <i class="fa-solid fa-toggle-on"></i>
                            <span style="margin-left: 10px;">Estados</span>
                        </button>
                        <button class="btn btn-outline-light"
                            style="margin: 5px; display:flex; align-items: center; justify-content:center;"
                            onclick="window.location.href='{{ route('categorias') }}'">
                            <i class="fa-solid fa-list"></i>
                            <span style="margin-left: 10px;">Categorias</span>
                        </button>
                        <button class="btn btn-outline-light"
                            style="margin: 5px; display:flex; align-items: center; justify-content:center;"
                            onclick="window.location.href='{{ route('proveedores') }}'">
                            <i class="fa-solid fa-truck"></i>
                            <span style="margin-left: 10px;">Proveedores</span>
                        </button>
                    @endif


                </nav>
            </div>

            <!-- Content -->

            <div class="container col-10" style="padding-top: 15px; padding-right: 30px;">
                @yield('content')
            </div>

        </div>
    </div>

</body>

</html>
