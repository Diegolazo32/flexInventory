<?php

namespace Database\Seeders;

use Database\Factories\PermisosFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Metodos
        // 1 = GET
        // 2 = POST
        // 3 = PUT
        // 4 = DELETE

        $permisos = [
            //Usuarios
            [
                'nombre' => 'ver-usuarios',
                ////'ruta' => 'users',
                'descripcion' => 'Ver usuarios',
                //'endpoint' => '/users',
                'grupo' => 1,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-usuarios',
                ////'ruta' => 'users.store',
                'descripcion' => 'Editar usuarios',
                //'endpoint' => '/users/store',
                'grupo' => 1,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-usuarios',
                ////'ruta' => 'users.edit',
                'descripcion' => 'Editar usuarios',
                //'endpoint' => '/users/edit/{id}',
                'grupo' => 1,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-usuarios',
                ////'ruta' => 'users.delete',
                'descripcion' => 'Eliminar usuarios',
                //'endpoint' => '/users/delete/{id}',
                'grupo' => 1,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'reestablecer-password',
                ////'ruta' => 'users.restore',
                'descripcion' => 'Reestablecer contraseña',
                //'endpoint' => '/users/rstpsw/{id}',
                'grupo' => 1,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-usuarios',
                ////'ruta' => 'allUsers',
                'descripcion' => 'Obtener todos los usuarios',
                //'endpoint' => '/allUsers',
                'grupo' => 1,
                //'metodo' => 1,
            ],

            //Unidades
            [
                'nombre' => 'ver-unidades',
                ////'ruta' => 'unidades',
                'descripcion' => 'Ver unidades',
                //'endpoint' => '/unidades',
                'grupo' => 2,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-unidades',
                ////'ruta' => 'unidades.store',
                'descripcion' => 'Crear unidades',
                //'endpoint' => '/unidades/store',
                'grupo' => 2,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-unidades',
                ////'ruta' => 'unidades.edit',
                'descripcion' => 'Editar unidades',
                //'endpoint' => '/unidades/edit/{id}',
                'grupo' => 2,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-unidades',
                ////'ruta' => 'unidades.delete',
                'descripcion' => 'Eliminar unidades',
                //'endpoint' => '/unidades/delete/{id}',
                'grupo' => 2,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-unidades',
                ////'ruta' => 'allUnidades',
                'descripcion' => 'Obtener todas las unidades',
                //'endpoint' => '/allUnidades',
                'grupo' => 2,
                //'metodo' => 1,
            ],

            //Estados
            [
                'nombre' => 'ver-estados',
                ////'ruta' => 'estados',
                'descripcion' => 'Ver estados',
                //'endpoint' => '/estados',
                'grupo' => 5,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-estados',
                ////'ruta' => 'estados.store',
                'descripcion' => 'Crear estados',
                //'endpoint' => '/estados/store',
                'grupo' => 5,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-estados',
                ////'ruta' => 'estados.edit',
                'descripcion' => 'Editar estados',
                //'endpoint' => '/estados/edit/{id}',
                'grupo' => 5,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-estados',
                ////'ruta' => 'estados.delete',
                'descripcion' => 'Eliminar estados',
                //'endpoint' => '/estados/delete/{id}',
                'grupo' => 5,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-estados',
                ////'ruta' => 'allEstados',
                'descripcion' => 'Obtener todos los estados',
                //'endpoint' => '/allEstados',
                'grupo' => 5,
                //'metodo' => 1,
            ],

            //Categorias
            [
                'nombre' => 'ver-categorias',
                ////'ruta' => 'categorias',
                'descripcion' => 'Ver categorias',
                //'endpoint' => '/categorias',
                'grupo' => 6,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-categorias',
                ////'ruta' => 'categorias.store',
                'descripcion' => 'Crear categorias',
                //'endpoint' => '/categorias/store',
                'grupo' => 6,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-categorias',
                ////'ruta' => 'categorias.edit',
                'descripcion' => 'Editar categorias',
                //'endpoint' => '/categorias/edit/{id}',
                'grupo' => 6,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-categorias',
                ////'ruta' => 'categorias.delete',
                'descripcion' => 'Eliminar categorias',
                //'endpoint' => '/categorias/delete/{id}',
                'grupo' => 6,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-categorias',
                ////'ruta' => 'allCategorias',
                'descripcion' => 'Obtener todas las categorias',
                //'endpoint' => '/allCategorias',
                'grupo' => 6,
                //'metodo' => 1,
            ],

            //Clientes
            [
                'nombre' => 'ver-clientes',
                ////'ruta' => 'clientes',
                'descripcion' => 'Ver clientes',
                //'endpoint' => '/clientes',
                'grupo' => 9,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-clientes',
                ////'ruta' => 'clientes.store',
                'descripcion' => 'Crear clientes',
                //'endpoint' => '/clientes/store',
                'grupo' => 9,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-clientes',
                ////'ruta' => 'clientes.edit',
                'descripcion' => 'Editar clientes',
                //'endpoint' => '/clientes/edit/{id}',
                'grupo' => 9,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-clientes',
                ////'ruta' => 'clientes.delete',
                'descripcion' => 'Eliminar clientes',
                //'endpoint' => '/clientes/delete/{id}',
                'grupo' => 9,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-clientes',
                ////'ruta' => 'allClientes',
                'descripcion' => 'Obtener todos los clientes',
                //'endpoint' => '/allClientes',
                'grupo' => 9,
                //'metodo' => 1,
            ],

            //TipoVenta
            [
                'nombre' => 'ver-tipoVenta',
                ////'ruta' => 'tipoVenta',
                'descripcion' => 'Ver tipo de venta',
                //'endpoint' => '/tipoVenta',
                'grupo' => 11,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-tipoVenta',
                ////'ruta' => 'tipoVenta.store',
                'descripcion' => 'Crear tipo de venta',
                //'endpoint' => '/tipoVenta/store',
                'grupo' => 11,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-tipoVenta',
                ////'ruta' => 'tipoVenta.edit',
                'descripcion' => 'Editar tipo de venta',
                //'endpoint' => '/tipoVenta/edit/{id}',
                'grupo' => 11,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-tipoVenta',
                ////'ruta' => 'tipoVenta.delete',
                'descripcion' => 'Eliminar tipo de venta',
                //'endpoint' => '/tipoVenta/delete/{id}',
                'grupo' => 11,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-tipoVenta',
                ////'ruta' => 'allTipoVenta',
                'descripcion' => 'Obtener todos los tipos de venta',
                //'endpoint' => '/allTipoVenta',
                'grupo' => 11,
                //'metodo' => 1,
            ],

            //Productos
            [
                'nombre' => 'ver-productos',
                ////'ruta' => 'productos',
                'descripcion' => 'Ver productos',
                //'endpoint' => '/productos',
                'grupo' => 7,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-productos',
                ////'ruta' => 'productos.store',
                'descripcion' => 'Crear productos',
                //'endpoint' => '/productos/store',
                'grupo' => 7,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-productos',
                ////'ruta' => 'productos.edit',
                'descripcion' => 'Editar productos',
                //'endpoint' => '/productos/edit/{id}',
                'grupo' => 7,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-productos',
                ////'ruta' => 'productos.delete',
                'descripcion' => 'Eliminar productos',
                //'endpoint' => '/productos/delete/{id}',
                'grupo' => 7,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-productos',
                ////'ruta' => 'allProductos',
                'descripcion' => 'Obtener todos los productos',
                //'endpoint' => '/allProductos',
                'grupo' => 7,
                //'metodo' => 1,
            ],

            //Roles
            [
                'nombre' => 'ver-roles',
                ////'ruta' => 'roles',
                'descripcion' => 'Ver roles',
                //'endpoint' => '/roles',
                'grupo' => 3,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-roles',
                ////'ruta' => 'roles.store',
                'descripcion' => 'Crear roles',
                //'endpoint' => '/roles/store',
                'grupo' => 3,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-roles',
                ////'ruta' => 'roles.edit',
                'descripcion' => 'Editar roles',
                //'endpoint' => '/roles/edit/{id}',
                'grupo' => 3,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-roles',
                ////'ruta' => 'roles.delete',
                'descripcion' => 'Eliminar roles',
                //'endpoint' => '/roles/delete/{id}',
                'grupo' => 3,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-roles',
                ////'ruta' => 'allRoles',
                'descripcion' => 'Obtener todos los roles',
                //'endpoint' => '/allRoles',
                'grupo' => 3,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'asignar-permisos-rol	',
                ////'ruta' => 'roles.permisos',
                'descripcion' => 'Asignar los permisos a un rol',
                //'endpoint' => '/roles/permisos/{id}',
                'grupo' => 3,
                //'metodo' => 2,
            ],

            //Permisos
            [
                'nombre' => 'ver-permisos',
                ////'ruta' => 'permisos',
                'descripcion' => 'Ver permisos',
                //'endpoint' => '/permisos',
                'grupo' => 4,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-permisos',
                ////'ruta' => 'permisos.store',
                'descripcion' => 'Crear permisos',
                //'endpoint' => '/permisos/store',
                'grupo' => 4,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-permisos',
                ////'ruta' => 'permisos.edit',
                'descripcion' => 'Editar permisos',
                //'endpoint' => '/permisos/edit/{id}',
                'grupo' => 4,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-permisos',
                ////'ruta' => 'permisos.delete',
                'descripcion' => 'Eliminar permisos',
                //'endpoint' => '/permisos/delete/{id}',
                'grupo' => 4,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-permisos',
                ////'ruta' => 'allPermisos',
                'descripcion' => 'Obtener todos los permisos',
                //'endpoint' => '/allPermisos',
                'grupo' => 4,
                //'metodo' => 1,
            ],

            //Dashboard
            [
                'nombre' => 'ver-dashboard',
                //'ruta' => 'dashboard',
                'descripcion' => 'Ver dashboard',
                //'endpoint' => '/dashboard',
                'grupo' => 12,
                //'metodo' => 1,
            ],

            //Pantalla de reestablecer password
            [
                'nombre' => 'ver-rstpsw',
                //'ruta' => 'password',
                'descripcion' => 'Ver pantalla de reestablecer contraseña',
                //'endpoint' => '/auth/password/{id}',
                'grupo' => 15,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'reestablecer-rstpsw',
                //'ruta' => 'updatePassword',
                'descripcion' => 'Reestablecer contraseña desde pantalla de reestablecer contraseña',
                //'endpoint' => '/auth/password/{id}',
                'grupo' => 15,
                //'metodo' => 2,
            ],

            //Proveedores
            [
                'nombre' => 'ver-proveedores',
                //'ruta' => 'proveedores',
                'descripcion' => 'Ver proveedores',
                //'endpoint' => '/proveedores',
                'grupo' => 8,
                //'metodo' => 1,
            ],
            [
                'nombre' => 'crear-proveedores',
                //'ruta' => 'proveedores.store',
                'descripcion' => 'Crear proveedores',
                //'endpoint' => '/proveedores/store',
                'grupo' => 8,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'editar-proveedores',
                //'ruta' => 'proveedores.edit',
                'descripcion' => 'Editar proveedores',
                //'endpoint' => '/proveedores/edit/{id}',
                'grupo' => 8,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'eliminar-proveedores',
                //'ruta' => 'proveedores.delete',
                'descripcion' => 'Eliminar proveedores',
                //'endpoint' => '/proveedores/delete/{id}',
                'grupo' => 8,
                //'metodo' => 2,
            ],
            [
                'nombre' => 'obtener-all-proveedores',
                //'ruta' => 'allProveedores',
                'descripcion' => 'Obtener todos los proveedores',
                //'endpoint' => '/allProveedores',
                'grupo' => 8,
                //'metodo' => 1,
            ],
        ];

        foreach ($permisos as $permiso) {
            PermisosFactory::new()->create($permiso);
        }
    }
}
