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
    <nav class="navbar navbar-dark bg-dark" style="padding: 10px;" id="Menu">

        <div class="row d-flex align-items-center titleBar" style="width: 100%;">
            <div class="col-2 d-flex justify-content-center">
                <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            <div class="col-8 d-flex justify-content-center">
                <!-- Logo circular -->
                <a href="{{ route('dashboard') }}" style="font-weight: normal" class="nombreMarca">
                    <img src="{{ asset('storage/logo/logo_empresa.jpg') }}" alt="Logo" class="rounded-circle"
                        style="width: 50px; height: 50px;">
                </a>

                <a class="navbar-brand nombreMarca" href="{{ route('dashboard') }}"
                    style="margin-left:15px; font-weight: normal"><?php
                    use App\Models\empresa;
                    $empresaName = empresa::select('nombre')->first();

                    if ($empresaName == null) {
                        echo 'Flex Inventory';
                    } else {
                        echo $empresaName->nombre;
                    }

                    ?></a>
            </div>
            <div class="col-2 d-flex justify-content-center">

                <div class="dropdown-center">
                    <button class="btn dropdown-toggle btn-outline-light" data-bs-display="static" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->nombre }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-md-start ">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuracion</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesion</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>

    <!--SideBar-->
    <div class="container-fluid">

        <div data-bs-theme="dark" class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel" style="max-width: 225px;">
            <ul class="navbar-nav navbar-nav-scroll" style="--bs-scroll-height: 100%">
                <!-- Logo circular -->
                <div class="offcanvas-title">
                    <li class="nav-item" style="width: 100%;">
                        <div class="d-flex justify-content-center align-items-center"
                            style="margin-bottom: 20px; margin-top: 20px;">
                            <a href="{{ route('dashboard') }}" style="font-weight: normal">
                                <img src="{{ asset('storage/logo/logo_empresa.jpg') }}" alt="Logo"
                                    class="rounded-circle" style="width: 50px; height: 50px;">
                            </a>
                        </div>
                    </li>
                </div>

                <div class="offcanvas-body" style="display: flex; flex-direction: column; gap: 10px; width: 100%;">
                    <!--Inicio -->
                    <li class="nav-item" style="width: 100%;">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            onClick="window.location.href='{{ route('dashboard') }}'">
                            <i class="fa-solid fa-house"></i>
                            <span>Dashboard</span>
                        </button>
                    </li>

                    <!-- Admin. Empresa -->
                    <li class="nav-item" style="width: 100%">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseNegocio" aria-expanded="false"
                            aria-controls="collapseNegocio">
                            <i class="fa-solid fa-briefcase"></i>
                            <span> Mi negocio</span>
                        </button>
                    </li>

                    <div class="collapse" id="collapseNegocio" style="margin-left: 20px;">
                        <!-- Mi negocio -->
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('empresa') }}'">
                                <i class="fa-regular fa-file-lines"></i>
                                <span>Información</span>
                            </button>
                        </li>

                    </div>

                    <!-- Admin. Usuarios -->
                    <li class="nav-item" style="width: 100%">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false"
                            aria-controls="collapseUsuarios">
                            <i class="fa-solid fa-user-gear"></i>
                            <span> Admin. Usuarios</span>
                        </button>
                    </li>

                    <!-- Collapse Usuarios -->
                    <div class="collapse" id="collapseUsuarios" style="margin-left: 20px;">
                        <!-- Usuarios -->
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('users') }}'">
                                <i class="fa-solid fa-user"></i>
                                <span>Usuarios</span>
                            </button>
                        </li>

                        <!-- Roles -->
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('roles') }}'">
                                <i class="fa-solid fa-image-portrait"></i>
                                <span>Roles</span>
                            </button>
                        </li>

                        <!-- Permisos -->
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('permisos') }}'">
                                <i class="fa-solid fa-key"></i>
                                <span>Permisos</span>
                            </button>
                        </li>
                    </div>

                    <!-- Parametros -->
                    <li class="nav-item" style="width: 100%">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseParametros" aria-expanded="false"
                            aria-controls="collapseParametros">
                            <i class="fa-solid fa-gear"></i>
                            <span> Parametros</span>
                        </button>
                    </li>

                    <!-- Collapse Parametros -->
                    <div class="collapse" id="collapseParametros" style="margin-left: 20px;">
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('unidades') }}'">
                                <i class="fa-solid fa-list"></i>
                                <span>Unidades</span>
                            </button>
                        </li>

                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('categorias') }}'">
                                <i class="fa-solid fa-list"></i>
                                <span>Categorias</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('cajas') }}'">
                                <i class="fa-solid fa-cash-register"></i>
                                <span>Cajas</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('estados') }}'">
                                <i class="fa-solid fa-toggle-on"></i>
                                <span>Estados</span>
                            </button>
                        </li>
                    </div>

                    <!-- Inventario -->
                    <li class="nav-item" style="width: 100%">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseInventario" aria-expanded="false"
                            aria-controls="collapseInventario">
                            <i class="fa-solid fa-boxes-packing"></i>
                            <span> Inventario</span>
                        </button>
                    </li>

                    <!-- Collapse Inventario -->
                    <div class="collapse" id="collapseInventario" style="margin-left: 20px;">
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('inventario') }}'">
                                <i class="fa-solid fa-warehouse"></i>
                                <span>Apertura</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('clientes') }}'">
                                <i class="fa-solid fa-users"></i>
                                <span>Clientes</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('proveedores') }}'">
                                <i class="fa-solid fa-truck"></i>
                                <span>Proveedores</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('productos') }}'">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <span>Productos</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('lotes') }}'">
                                <i class="fa-solid fa-cart-flatbed"></i>
                                <span>Lotes</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('compras') }}'">
                                <i class="fa-solid fa-basket-shopping"></i>
                                <span>Compras</span>
                            </button>
                        </li>
                        <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('kardex') }}'">
                                <i class="fa-solid fa-warehouse"></i>
                                <span>Kardex</span>
                            </button>
                        </li>
                    </div>

                </div>
            </ul>

        </div>

        <div class="row">
            <!-- Content -->
            <div class="container col-lg-10" style="padding-top: 15px;">
                @yield('content')
            </div>

        </div>
    </div>

</body>

<script>
    new Vue({
        el: '#Menu',
        data: {
            empresaName: '',
        },
        methods: {
            async getEmpresaName() {
                axios({
                    method: 'get',
                    url: '/empresaName',
                }).then((response) => {
                    this.empresaName = response.data;

                    if (this.empresaName == null) {
                        this.empresaName = 'Flex Inventory';
                    }

                }).catch((error) => {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.getEmpresaName();
        },
    })
</script>

</html>
