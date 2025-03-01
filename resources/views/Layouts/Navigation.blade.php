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

    @vite('resources/css/app.css')



</head>

<body>
    <?php
    use App\Models\empresa;
    use App\Models\rolPermiso;

    $empresa = empresa::first();
    $rol = Auth::user()->rol;
    $permisos = rolPermiso::where('rol', Auth::user()->rol)->get();
    $permisos = $permisos->pluck('permiso');

    if ($empresa == null) {
        $empresa = new empresa();
        $empresa->nombre = 'Flex Inventory';
        $empresa->logo = 'logo/empresa_logo.jpg';
    }

    ?>
    <!-- container-fluid hace que el contenido se ajuste al ancho de la pantalla -->

    <!-- NavBar -->
    <nav class="navbar navbar-dark bg-dark" style="padding: 10px;" id="Menu">

        <div class="row d-flex align-items-center titleBar justify-content-center" style="width: 100%;">
            <div class="col-2 d-flex justify-content-center">
                <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            <div class="col-6 d-flex justify-content-center">
                <!-- Logo circular -->
                <a href="{{ route('dashboard') }}" style="font-weight: normal" class="nombreMarca">
                    <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo" class="rounded-circle"
                        style="width: 50px; height: 50px;object-fit: cover;">
                </a>

            </div>
            <div class="col-2 d-flex justify-content-center">

                <div class="dropdown-center">
                    <button class="btn dropdown-toggle btn-outline-light" data-bs-display="static" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->nombre }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-md-end ">
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesion</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </nav>

    <!--SideBar-->
    <div class="container-fluid">

        <div data-bs-theme="dark" class=" offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel"
            style="max-width: 250px; display: flex; flex-direction: column; justify-content: space-between;">
            <ul class="navbar-nav navbar-nav-scroll" style="--bs-scroll-height: 100%">
                <!-- Logo circular -->
                <div class="offcanvas-title">
                    <li class="nav-item" style="width: 100%;">
                        <div class="d-flex justify-content-center align-items-center"
                            style="margin-bottom: 20px; margin-top: 20px;">
                            <a href="{{ route('dashboard') }}" style="font-weight: normal">
                                <img src="{{ asset('storage/' . $empresa->logo) }}" alt="Logo"
                                    class="rounded-circle" style="width: 50px; height: 50px;object-fit: cover;">
                            </a>
                        </div>
                    </li>

                    <!-- Nombre de la empresa -->
                    <li class="nav-item" style="width: 100%;">
                        <div class="d-flex justify-content-center align-items-center" style="text-align: center; padding-right: 10px; padding-left: 10px;">
                            <h5 style="font-weight: normal" class="nombreMarca">
                                {{ $empresa->nombre ?? 'Flex Inventory' }}
                            </h5>
                        </div>
                    </li>

                </div>

                <div class="offcanvas-body" style="display: flex; flex-direction: column; gap: 10px; width: 100%;">
                    <!--Inicio -->
                    <li class="nav-item" style="width: 100%;">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            onClick="window.location.href='{{ route('dashboard') }}'">
                            <i class="fa-solid fa-house"></i>
                            <span>Inicio</span>
                        </button>
                    </li>

                    @if ($permisos->contains(2))
                        <!-- Admin. Empresa -->
                        <li class="nav-item" style="width: 100%">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseNegocio" aria-expanded="false"
                                aria-controls="collapseNegocio">
                                <i class="fa-solid fa-briefcase"></i>
                                <span> Mi negocio</span>
                            </button>
                        </li>
                    @endif

                    <div class="collapse" id="collapseNegocio" style="margin-left: 20px;">
                        <!-- Mi negocio -->
                        @if ($permisos->contains(2))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('empresa') }}'">
                                    <i class="fa-regular fa-file-lines"></i>
                                    <span>Información</span>
                                </button>
                            </li>
                        @endif

                    </div>

                    <!-- Admin. Usuarios -->
                    @if ($permisos->contains(5) || $permisos->contains(11) || $permisos->contains(17))
                        <li class="nav-item" style="width: 100%">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false"
                                aria-controls="collapseUsuarios">
                                <i class="fa-solid fa-user-gear"></i>
                                <span> Admin. Usuarios</span>
                            </button>
                        </li>
                    @endif


                    <!-- Collapse Usuarios -->
                    <div class="collapse" id="collapseUsuarios" style="margin-left: 20px;">
                        <!-- Usuarios -->
                        @if ($permisos->contains(5))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('users') }}'">
                                    <i class="fa-solid fa-user"></i>
                                    <span>Usuarios</span>
                                </button>
                            </li>
                        @endif

                        <!-- Roles -->
                        @if ($permisos->contains(11))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('roles') }}'">
                                    <i class="fa-solid fa-image-portrait"></i>
                                    <span>Roles</span>
                                </button>
                            </li>
                        @endif


                        <!-- Permisos -->
                        <!--@if ($permisos->contains(17))
<li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('permisos') }}'">
                                    <i class="fa-solid fa-key"></i>
                                    <span>Permisos</span>
                                </button>
                            </li>
@endif-->

                        <!--@if ($permisos->contains(17))
<li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('auditoria') }}'">
                                    <i class="fa-solid fa-key"></i>
                                    <span>Auditoria</span>
                                </button>
                            </li>
@endif-->

                    </div>

                    <!-- Parametros -->
                    @if ($permisos->contains(22) || $permisos->contains(27) || $permisos->contains(32) || $permisos->contains(37))
                        <li class="nav-item" style="width: 100%">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseParametros" aria-expanded="false"
                                aria-controls="collapseParametros">
                                <i class="fa-solid fa-gear"></i>
                                <span> Parametros</span>
                            </button>
                        </li>
                    @endif

                    <!-- Collapse Parametros -->
                    <div class="collapse" id="collapseParametros" style="margin-left: 20px;">
                        @if ($permisos->contains(22))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('unidades') }}'">
                                    <i class="fa-solid fa-ruler-horizontal"></i>
                                    <span>Unidades</span>
                                </button>
                            </li>
                        @endif

                        @if ($permisos->contains(27))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('categorias') }}'">
                                    <i class="fa-solid fa-tags"></i>
                                    <span>Categorias</span>
                                </button>
                            </li>
                        @endif

                        @if ($permisos->contains(32))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('cajas') }}'">
                                    <i class="fa-solid fa-cash-register"></i>
                                    <span>Cajas</span>
                                </button>
                            </li>
                        @endif

                        @if ($permisos->contains(37))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('estados') }}'">
                                    <i class="fa-solid fa-toggle-on"></i>
                                    <span>Estados</span>
                                </button>
                            </li>
                        @endif
                    </div>

                    <!-- Inventario -->
                    @if (
                        $permisos->contains(42) ||
                            $permisos->contains(46) ||
                            $permisos->contains(51) ||
                            $permisos->contains(56) ||
                            $permisos->contains(60) ||
                            $permisos->contains(64) ||
                            $permisos->contains(70))
                        <li class="nav-item" style="width: 100%">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseInventario" aria-expanded="false"
                                aria-controls="collapseInventario">
                                <i class="fa-solid fa-boxes-packing"></i>
                                <span> Admin. Inventario</span>
                            </button>
                        </li>
                    @endif

                    <!-- Collapse Inventario -->
                    <div class="collapse" id="collapseInventario" style="margin-left: 20px;">
                        @if ($permisos->contains(42))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('inventario') }}'">
                                    <i class="fa-solid fa-warehouse"></i>
                                    <span>Apertura/Cierre</span>
                                </button>
                            </li>
                        @endif

                        @if ($permisos->contains(46))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('clientes') }}'">
                                    <i class="fa-solid fa-users"></i>
                                    <span>Clientes</span>
                                </button>
                            </li>
                        @endif


                        @if ($permisos->contains(51))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('proveedores') }}'">
                                    <i class="fa-solid fa-truck"></i>
                                    <span>Proveedores</span>
                                </button>
                            </li>
                        @endif


                        @if ($permisos->contains(56))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('productos') }}'">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                    <span>Productos</span>
                                </button>
                            </li>
                        @endif


                        @if ($permisos->contains(60))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('lotes') }}'">
                                    <i class="fa-solid fa-cart-flatbed"></i>
                                    <span>Lotes</span>
                                </button>
                            </li>
                        @endif


                        @if ($permisos->contains(64))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('compras') }}'">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    <span>Compras</span>
                                </button>
                            </li>
                        @endif


                        @if ($permisos->contains(70))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('kardex') }}'">
                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                    <span>Kardex</span>
                                </button>
                            </li>
                        @endif
                    </div>

                    <!--  Ventas -->
                    <!--<li class="nav-item" style="width: 100%">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseVenta" aria-expanded="false"
                            aria-controls="collapseVenta">
                            <i class="fa-solid fa-boxes-packing"></i>
                            <span> Ventas </span>
                        </button>
                    </li>-->

                    <!-- Collapse venta -->
                    <!-- <div class="collapse" id="collapseVenta" style="margin-left: 20px;">

                       <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                onClick="window.location.href='{{ route('empresa') }}'">
                                <i class="fa-regular fa-file-lines"></i>
                                <span>Menu caja</span>
                            </button>
                        </li>

                    </div>-->

                    <!-- Reportes -->

                    @if ($permisos->contains(73) || $permisos->contains(75))
                        <li class="nav-item" style="width: 100%">
                            <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseReportes" aria-expanded="false"
                                aria-controls="collapseReportes">
                                <i class="fa-solid fa-file"></i>
                                <span> Reportes </span>
                            </button>
                        </li>
                    @endif

                    <!-- Collapse reportes -->
                    <div class="collapse" id="collapseReportes" style="margin-left: 20px;">

                        @if ($permisos->contains(73))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('reportes.productos') }}'">
                                    <i class="fa-regular fa-file-lines"></i>
                                    <span>Reporte de productos</span>
                                </button>
                            </li>
                        @endif

                        @if ($permisos->contains(75))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('reportes.movimientos') }}'">
                                    <i class="fa-regular fa-file-lines"></i>
                                    <span>Reporte de movimientos</span>
                                </button>
                            </li>
                        @endif

                    </div>

                    <!-- Registros -->
                    <li class="nav-item" style="width: 100%">
                        <button class="btn btn-outline-light" style="width: 100%;" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseRegistros" aria-expanded="false"
                            aria-controls="collapseRegistros">
                            <i class="fa-solid fa-list-ul"></i>
                            <span> Registros </span>
                        </button>
                    </li>

                    <!-- Collapse Registros -->
                    <div class="collapse" id="collapseRegistros" style="margin-left: 20px;">
                        @if ($permisos->contains(70))
                            <li class="nav-item" style="width: 90%; margin-bottom: 10px;">
                                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                                    onClick="window.location.href='{{ route('kardex.movimientos') }}'">
                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                    <span>Movimientos de kardex</span>
                                </button>
                            </li>
                        @endif
                    </div>

                </div>
            </ul>

            <div style="margin-top: auto; margin-bottom: 25px; margin-left:20px ; margin-right: 20px;">
                <hr style="background-color: white;">

                <button class="btn btn-outline-light" style="width: 100%; margin-bottom: 10px;" type="button"
                    onclick="window.location.href='{{ route('ayuda') }}'">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Información</span>
                </button>

                <button class="btn btn-outline-light" style="width: 100%;" type="button"
                    onClick="window.location.href='{{ route('logout') }}'">
                    <i class="fa-solid fa-door-open"></i>
                    <span>Cerrar sesion</span>
                </button>
            </div>

        </div>

        <div class="row">
            <!-- Content -->
            <div class="container col-lg-11" style="padding-top: 15px; margin-bottom: 50px;">
                @yield('content')
            </div>

        </div>
    </div>

</body>

</html>
