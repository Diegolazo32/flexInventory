<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\MovimientosCajaController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\reportesController;
use App\Http\Controllers\ResolucionTicketsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RolPermisoController;
use App\Http\Controllers\TipoVentaController;
use App\Http\Controllers\TurnosController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});



//Auth
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('AuthLogin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {

    //Acess Denied
    Route::get('/denied', function () {
        return view('layouts.accessDenied');
    })->name('denied');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Cambiar contraseña
    Route::get('/auth/password/{id}', [AuthController::class, 'password'])->name('password');
    Route::post('/auth/password/{id}', [AuthController::class, 'updatePassword'])->name('updatePassword');

    //Usuarios
    Route::controller(UserController::class)->group(function () {
        Route::get('/allUsers', [UserController::class, 'getAllUsers'])->name('allUsers');
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/edit/{id}', [UserController::class, 'update'])->name('users.edit');
        Route::post('/users/rstpsw/{id}', [UserController::class, 'resetPassword'])->name('users.restore');
        Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    });

    //Unidades
    Route::controller(UnidadesController::class)->group(function () {
        Route::get('/allUnidades', [UnidadesController::class, 'getAllUnidades'])->name('allUnidades');
        Route::get('/unidades', [UnidadesController::class, 'index'])->name('unidades');
        Route::post('/unidades/store', [UnidadesController::class, 'store'])->name('unidades.store');
        Route::post('/unidades/edit/{id}', [UnidadesController::class, 'update'])->name('unidades.edit');
        Route::delete('/unidades/delete/{id}', [UnidadesController::class, 'delete'])->name('unidades.delete');
    });

    //Estados
    Route::controller(EstadosController::class)->group(function () {
        Route::get('/allEstados', [EstadosController::class, 'getAllEstados'])->name('allEstados');
        Route::get('/estados', [EstadosController::class, 'index'])->name('estados');
        Route::post('/estados/store', [EstadosController::class, 'store'])->name('estados.store');
        Route::post('/estados/edit/{id}', [EstadosController::class, 'update'])->name('estados.edit');
        Route::delete('/estados/delete/{id}', [EstadosController::class, 'delete'])->name('estados.delete');
    });

    //Categorias
    Route::controller(CategoriaController::class)->group(function () {
        Route::get('/allCategorias', [CategoriaController::class, 'getAllCategorias'])->name('allCategorias');
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
        Route::post('/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::post('/categorias/edit/{id}', [CategoriaController::class, 'update'])->name('categorias.edit');
        Route::delete('/categorias/delete/{id}', [CategoriaController::class, 'delete'])->name('categorias.delete');
    });

    //Proveedores
    Route::controller(ProveedoresController::class)->group(function () {
        Route::get('/allProveedores', [ProveedoresController::class, 'getAllProveedores'])->name('allProveedores');
        Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('proveedores');
        Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedores.store');
        Route::post('/proveedores/edit/{id}', [ProveedoresController::class, 'update'])->name('proveedores.edit');
        Route::delete('/proveedores/delete/{id}', [ProveedoresController::class, 'delete'])->name('proveedores.delete');
    });

    //Clientes
    Route::controller(ClientesController::class)->group(function () {
        Route::get('/allClientes', [ClientesController::class, 'getAllClientes'])->name('allClientes');
        Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes');
        Route::post('/clientes/store', [ClientesController::class, 'store'])->name('clientes.store');
        Route::post('/clientes/edit/{id}', [ClientesController::class, 'update'])->name('clientes.edit');
        Route::delete('/clientes/delete/{id}', [ClientesController::class, 'delete'])->name('clientes.delete');
    });

    //TipoVenta
    Route::controller(TipoVentaController::class)->group(function () {
        Route::get('/allTipoVenta', [TipoVentaController::class, 'getAllTipoVenta'])->name('allTipoVenta');
        Route::get('/tipoVenta', [TipoVentaController::class, 'index'])->name('tipoVenta');
        Route::post('/tipoVenta/store', [TipoVentaController::class, 'store'])->name('tipoVenta.store');
        Route::post('/tipoVenta/edit/{id}', [TipoVentaController::class, 'update'])->name('tipoVenta.edit');
        Route::delete('/tipoVenta/delete/{id}', [TipoVentaController::class, 'delete'])->name('tipoVenta.delete');
    });

    //Productos
    Route::controller(ProductosController::class)->group(function () {
        Route::get('/allProductos', [ProductosController::class, 'getAllProductos'])->name('allProductos');
        Route::get('/productos', [ProductosController::class, 'index'])->name('productos');
        Route::post('/productos/store', [ProductosController::class, 'store'])->name('productos.store');
        Route::post('/productos/edit/{id}', [ProductosController::class, 'update'])->name('productos.edit');
        Route::delete('/productos/delete/{id}', [ProductosController::class, 'delete'])->name('productos.delete');
    });

    //Roles
    Route::controller(RolesController::class)->group(function () {
        Route::get('/allRoles', [RolesController::class, 'getAllRoles'])->name('allRoles');
        Route::get('/roles', [RolesController::class, 'index'])->name('roles');
        Route::post('/roles/store', [RolesController::class, 'store'])->name('roles.store');
        Route::post('/roles/edit/{id}', [RolesController::class, 'update'])->name('roles.edit');
        Route::delete('/roles/delete/{id}', [RolesController::class, 'delete'])->name('roles.delete');
        Route::post('/roles/permisos/{id}', [RolesController::class, 'permisos'])->name('roles.permisos');
    });

    //Permisos
    Route::controller(PermisosController::class)->group(function () {
        Route::get('/allPermisos', [PermisosController::class, 'getAllPermisos'])->name('allPermisos');
        Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos');
        Route::post('/permisos/store', [PermisosController::class, 'store'])->name('permisos.store');
        Route::post('/permisos/edit/{id}', [PermisosController::class, 'update'])->name('permisos.edit');
        Route::delete('/permisos/delete/{id}', [PermisosController::class, 'delete'])->name('permisos.delete');
    });

    //Grupos
    Route::get('/allGrupos', [GruposController::class, 'getAllGrupos'])->name('allGrupos');

    //Permisos por Rol
    Route::controller(RolPermisoController::class)->group(function () {
        Route::get('/allRolPermiso', [RolPermisoController::class, 'getAllRolPermiso'])->name('allRolPermiso');
        Route::get('/permisosByRol/{id}', [RolPermisoController::class, 'getPermisosByRol'])->name('permisosByRol');
        Route::get('/permisosRolAuth', [RolPermisoController::class, 'permisosRolAuth'])->name('permisosRolAuth');
        Route::get('/checkPermisos/{id}', [RolPermisoController::class, 'checkPermisos'])->name('checkPermisos');
    });

    //Cajas
    Route::controller(CajaController::class)->group(function () {
        Route::get('/allCajas', [CajaController::class, 'getAllCajas'])->name('allCajas');
        Route::get('/cajas', [CajaController::class, 'index'])->name('cajas');
        Route::post('/cajas/store', [CajaController::class, 'store'])->name('cajas.store');
        Route::post('/cajas/edit/{id}', [CajaController::class, 'update'])->name('cajas.edit');
        Route::delete('/cajas/delete/{id}', [CajaController::class, 'delete'])->name('cajas.delete');
    });

    //Kardex
    Route::controller(KardexController::class)->group(function () {
        Route::get('/allKardex', [KardexController::class, 'getAllKardex'])->name('allKardex');
        Route::get('/kardex', [KardexController::class, 'index'])->name('kardex');
        Route::post('/kardex/store', [KardexController::class, 'store'])->name('kardex.store');
        Route::post('/kardex/edit/{id}', [KardexController::class, 'update'])->name('kardex.edit');
        Route::delete('/kardex/delete/{id}', [KardexController::class, 'delete'])->name('kardex.delete');
        Route::get('/kardex/movimientos', [KardexController::class, 'movimientos'])->name('kardex.movimientos');
        //Route::get('/kardex/movimientosByProduct/{id}', [KardexController::class, 'movimientosByProduct'])->name('kardex.movimientosByProduct');
    });

    //Inventario
    Route::controller(InventarioController::class)->group(function () {
        Route::get('/allInventario', [InventarioController::class, 'getAllInventario'])->name('allInventario');
        Route::get('/allInventarios', [InventarioController::class, 'getAllInventarios'])->name('allInventarios');
        Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario');
        Route::post('/inventario/open', [InventarioController::class, 'open'])->name('inventario.open');
        Route::post('/inventario/close', [InventarioController::class, 'close'])->name('inventario.close');
    });

    //Empresa
    Route::controller(EmpresaController::class)->group(function () {
        Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa');
        Route::get('/allEmpresa', [EmpresaController::class, 'getAllEmpresa'])->name('allEmpresa');
        Route::post('/empresa/edit/{id}', [EmpresaController::class, 'update'])->name('empresa.edit');
        Route::get('/empresa/logo', [EmpresaController::class, 'logo'])->name('empresa.logo');
        Route::get('/empresaName', [EmpresaController::class, 'empresaName'])->name('empresaName');
    });

    //Lotes
    Route::controller(LotesController::class)->group(function () {
        Route::get('/allLotes', [LotesController::class, 'getAllLotes'])->name('allLotes');
        Route::get('/lotes', [LotesController::class, 'index'])->name('lotes');
        Route::get('/lotesByInventario/{id}', [LotesController::class, 'getLotesByInventario'])->name('lotesByInventario');
        Route::get('/getLotes/{id}', [LotesController::class, 'getLotesByProduct'])->name('lotesByProduct');
        Route::post('/lotes/update/{id}', [LotesController::class, 'actualizarLotes'])->name('lotes.update');
        Route::post('/lotes/store', [LotesController::class, 'store'])->name('lotes.store');
    });

    //Compras
    Route::controller(ComprasController::class)->group(function () {
        Route::get('/allCompras', [ComprasController::class, 'getAllCompras'])->name('allCompras');
        Route::get('/compras', [ComprasController::class, 'index'])->name('compras');
        Route::post('/compras/store', [ComprasController::class, 'store'])->name('compras.store');
        Route::get('/getCompraDetails/{id}', [ComprasController::class, 'getCompraDetails'])->name('getCompraDetails');
        Route::post('/payCompra/{id}', [ComprasController::class, 'payCompra'])->name('payCompra');
        Route::post('/nullifyCompra/{id}', [ComprasController::class, 'nullifyCompra'])->name('nullifyCompra');
    });

    //Reportes
    Route::controller(reportesController::class)->group(function () {
        Route::get('/reportes/productos', [reportesController::class, 'productosIndex'])->name('reportes.productos');
        Route::get('/reportes/movimientos', [reportesController::class, 'movimientosIndex'])->name('reportes.movimientos');
        //Generar reporte de productos
        Route::post('/reportes/productos/generar', [reportesController::class, 'productosGenerar'])->name('reportes.productos.generar');
        Route::post('/reportes/movimientos/generar', [reportesController::class, 'movimientosGenerar'])->name('reportes.movimientos.generar');
    });

    //Auditorias
    Route::controller(AuditoriaController::class)->group(function () {
        Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria');
        Route::get('/allAuditorias', [AuditoriaController::class, 'getAllAudits'])->name('allAudits');
    });

    //Ayuda y manuales
    Route::get('/ayuda', function () {
        return view('ayuda.index');
    })->name('ayuda');


    //Queries
    Route::controller(QueriesController::class)->group(function () {
        Route::get('/getLotesVencimiento', [QueriesController::class, 'getLotesVencimiento'])->name('getLotesVencimiento');
        Route::get('/getProductosStockMinimo', [QueriesController::class, 'getProductosStockMinimo'])->name('getProductosStockMinimo');
        Route::get('/getProductosOverStock', [QueriesController::class, 'getProductosOverStock'])->name('getProductosOverStock');
    });

    //Ventas
    Route::controller(VentasController::class)->group(function () {
        Route::get('/allVentas', [VentasController::class, 'getAllVentas'])->name('allVentas');
        Route::get('/menuCajeros', [VentasController::class, 'menuCajeros'])->name('menuCajeros');
    });

    //Turnos
    Route::controller(TurnosController::class)->group(function () {
        Route::get('/allActiveTurnos', [TurnosController::class, 'getAllActiveTurnos'])->name('allTurnos');
        Route::post('/turnos/start', [TurnosController::class, 'startTurno'])->name('turnos.start');
        Route::post('/turnos/end', [TurnosController::class, 'endTurno'])->name('turnos.end');
    });

    //Tickets
    Route::controller(ResolucionTicketsController::class)->group(function () {
        Route::get('/allResoluciones', [ResolucionTicketsController::class, 'getAllTickets'])->name('allTickets');
        Route::get('/tickets', [ResolucionTicketsController::class, 'index'])->name('tickets');
        Route::post('/tickets/store', [ResolucionTicketsController::class, 'store'])->name('tickets.store');
        Route::post('/tickets/edit/{id}', [ResolucionTicketsController::class, 'update'])->name('tickets.edit');
        Route::delete('/tickets/delete/{id}', [ResolucionTicketsController::class, 'delete'])->name('tickets.delete');
    });

    //Movimientos de caja
    Route::controller(MovimientosCajaController::class)->group(function () {
        Route::get('/allMovimientos', [MovimientosCajaController::class, 'getAllMovimientos'])->name('allMovimientos');
        Route::get('/movimientos', [MovimientosCajaController::class, 'index'])->name('movimientos');
        Route::post('/movimientos/store', [MovimientosCajaController::class, 'store'])->name('movimientos.store');
    });

});
