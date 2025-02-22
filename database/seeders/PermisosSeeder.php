<?php

namespace Database\Seeders;

use App\Models\permisos;
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

        $permisos = [


            //Dashboard
            [
                // 'id' => 1,
                'nombre' => 'ver-dashboard',
                'descripcion' => 'Acceder a pantalla de inicio',
                'grupo' => 1,
            ],


            //Empresa
            [
                // 'id' => 2,
                'nombre' => 'ver-empresa',
                'descripcion' => 'Ver pantalla de informacion de empresa',
                'grupo' => 2,
            ],
            [
                // 'id' => 3,
                'nombre' => 'editar-empresa',
                'descripcion' => 'Editar datos de la empresa',
                'grupo' => 2,
            ],
            [
                // 'id' => 4,
                'nombre' => 'obtener-info-empresa',
                'descripcion' => 'Obtener informacion de la empresa',
                'grupo' => 2,
            ],


            //Usuarios
            [
                // 'id' => 5,
                'nombre' => 'ver-usuarios',
                'descripcion' => 'Ver listado de usuarios',
                'grupo' => 3,
            ],
            [
                // 'id' => 6,
                'nombre' => 'crear-usuarios',
                'descripcion' => 'Crear usuarios',
                'grupo' => 3,
            ],
            [
                // 'id' => 7,
                'nombre' => 'editar-usuarios',
                'descripcion' => 'Editar usuarios',
                'grupo' => 3,
            ],
            [
                // 'id' => 8,
                'nombre' => 'eliminar-usuarios',
                'descripcion' => 'Eliminar usuarios',
                'grupo' => 3,
            ],
            [
                // 'id' => 9,
                'nombre' => 'obtener-info-usuarios',
                'descripcion' => 'Obtener informacion de usuarios',
                'grupo' => 3,
            ],
            [
                // 'id' => 10,
                'nombre' => 'restablecer-password',
                'descripcion' => 'Restablecer contraseña de usuario',
                'grupo' => 3,
            ],


            //Roles
            [
                // 'id' => 11,
                'nombre' => 'ver-roles',
                'descripcion' => 'Ver listado de roles',
                'grupo' => 4,
            ],
            [
                // 'id' => 12,
                'nombre' => 'crear-roles',
                'descripcion' => 'Crear roles',
                'grupo' => 4,
            ],
            [
                // 'id' => 13,
                'nombre' => 'editar-roles',
                'descripcion' => 'Editar roles',
                'grupo' => 4,
            ],
            [
                // 'id' => 14,
                'nombre' => 'eliminar-roles',
                'descripcion' => 'Eliminar roles',
                'grupo' => 4,
            ],
            [
                // 'id' => 15,
                'nombre' => 'obtener-info-roles',
                'descripcion' => 'Obtener informacion de roles',
                'grupo' => 4,
            ],
            [
                // 'id' => 16,
                'nombre' => 'obtener-permisos-rol',
                'descripcion' => 'Obtener permisos de un rol especifico',
                'grupo' => 4,
            ],

            //Permisos
            [
                // 'id' => 17,
                'nombre' => 'ver-permisos',
                'descripcion' => 'Ver listado de permisos',
                'grupo' => 5,
            ],
            [
                // 'id' => 18,
                'nombre' => 'crear-permisos',
                'descripcion' => 'Crear permisos',
                'grupo' => 5,
            ],
            [
                // 'id' => 19,
                'nombre' => 'editar-permisos',
                'descripcion' => 'Editar permisos',
                'grupo' => 5,
            ],
            [
                // 'id' => 20,
                'nombre' => 'eliminar-permisos',
                'descripcion' => 'Eliminar permisos',
                'grupo' => 5,
            ],
            [
                // 'id' => 21,
                'nombre' => 'obtener-info-permisos',
                'descripcion' => 'Obtener informacion de permisos',
                'grupo' => 5,
            ],

            //Unidades
            [
                // 'id' => 22,
                'nombre' => 'ver-unidades',
                'descripcion' => 'Ver listado de unidades',
                'grupo' => 6,
            ],
            [
                // 'id' => 23,
                'nombre' => 'crear-unidades',
                'descripcion' => 'Crear unidades',
                'grupo' => 6,
            ],
            [
                // 'id' => 24,
                'nombre' => 'editar-unidades',
                'descripcion' => 'Editar unidades',
                'grupo' => 6,
            ],
            [
                // 'id' => 25,
                'nombre' => 'eliminar-unidades',
                'descripcion' => 'Eliminar unidades',
                'grupo' => 6,
            ],
            [
                // 'id' => 26,
                'nombre' => 'obtener-info-unidades',
                'descripcion' => 'Obtener informacion de unidades',
                'grupo' => 6,
            ],

            //Categorias
            [
                // 'id' => 27,
                'nombre' => 'ver-categorias',
                'descripcion' => 'Ver listado de categorias',
                'grupo' => 7,
            ],
            [
                // 'id' => 28,
                'nombre' => 'crear-categorias',
                'descripcion' => 'Crear categorias',
                'grupo' => 7,
            ],
            [
                // 'id' => 29,
                'nombre' => 'editar-categorias',
                'descripcion' => 'Editar categorias',
                'grupo' => 7,
            ],
            [
                // 'id' => 30,
                'nombre' => 'eliminar-categorias',
                'descripcion' => 'Eliminar categorias',
                'grupo' => 7,
            ],
            [
                // 'id' => 31,
                'nombre' => 'obtener-info-categorias',
                'descripcion' => 'Obtener informacion de categorias',
                'grupo' => 7,
            ],

            //Cajas
            [
                // 'id' => 32,
                'nombre' => 'ver-cajas',
                'descripcion' => 'Ver listado de cajas',
                'grupo' => 8,
            ],
            [
                // 'id' => 33,
                'nombre' => 'crear-cajas',
                'descripcion' => 'Crear cajas',
                'grupo' => 8,
            ],
            [
                // 'id' => 34,
                'nombre' => 'editar-cajas',
                'descripcion' => 'Editar cajas',
                'grupo' => 8,
            ],
            [
                // 'id' => 35,
                'nombre' => 'eliminar-cajas',
                'descripcion' => 'Eliminar cajas',
                'grupo' => 8,
            ],
            [
                // 'id' => 36,
                'nombre' => 'obtener-info-cajas',
                'descripcion' => 'Obtener informacion de cajas',
                'grupo' => 8,
            ],

            //Estados
            [
                // 'id' => 37,
                'nombre' => 'ver-estados',
                'descripcion' => 'Ver listado de estados',
                'grupo' => 9,
            ],
            [
                // 'id' => 38,
                'nombre' => 'crear-estados',
                'descripcion' => 'Crear estados',
                'grupo' => 9,
            ],
            [
                // 'id' => 39,
                'nombre' => 'editar-estados',
                'descripcion' => 'Editar estados',
                'grupo' => 9,
            ],
            [
                // 'id' => 40,
                'nombre' => 'eliminar-estados',
                'descripcion' => 'Eliminar estados',
                'grupo' => 9,
            ],
            [
                // 'id' => 41,
                'nombre' => 'obtener-info-estados',
                'descripcion' => 'Obtener informacion de estados',
                'grupo' => 9,
            ],

            //Apertura/Cierre de inventario
            [
                // 'id' => 42,
                'nombre' => 'ver-inventario',
                'descripcion' => 'Ver listado de inventario',
                'grupo' => 10,
            ],
            [
                // 'id' => 43,
                'nombre' => 'aperturar-inventario',
                'descripcion' => 'Aperturar inventario',
                'grupo' => 10,
            ],
            [
                // 'id' => 44,
                'nombre' => 'cerrar-inventario',
                'descripcion' => 'Cerrar inventario',
                'grupo' => 10,
            ],
            [
                // 'id' => 45,
                'nombre' => 'obtener-inventario',
                'descripcion' => 'Obtener informacion de inventario',
                'grupo' => 10,
            ],

            //Clientes
            [
                // 'id' => 46,
                'nombre' => 'ver-clientes',
                'descripcion' => 'Ver listado de clientes',
                'grupo' => 11,
            ],
            [
                // 'id' => 47,
                'nombre' => 'crear-clientes',
                'descripcion' => 'Crear clientes',
                'grupo' => 11,
            ],
            [
                // 'id' => 48,
                'nombre' => 'editar-clientes',
                'descripcion' => 'Editar clientes',
                'grupo' => 11,
            ],
            [
                // 'id' => 49,
                'nombre' => 'eliminar-clientes',
                'descripcion' => 'Eliminar clientes',
                'grupo' => 11,
            ],
            [
                // 'id' => 50,
                'nombre' => 'obtener-info-clientes',
                'descripcion' => 'Obtener informacion de clientes',
                'grupo' => 11,
            ],


            //Proveedores
            [
                // 'id' => 51,
                'nombre' => 'ver-proveedores',
                'descripcion' => 'Ver listado de proveedores',
                'grupo' => 12,
            ],
            [
                // 'id' => 52,
                'nombre' => 'crear-proveedores',
                'descripcion' => 'Crear proveedores',
                'grupo' => 12,
            ],
            [
                // 'id' => 53,
                'nombre' => 'editar-proveedores',
                'descripcion' => 'Editar proveedores',
                'grupo' => 12,
            ],
            [
                // 'id' => 54,
                'nombre' => 'eliminar-proveedores',
                'descripcion' => 'Eliminar proveedores',
                'grupo' => 12,
            ],
            [
                // 'id' => 55,
                'nombre' => 'obtener-info-proveedores',
                'descripcion' => 'Obtener informacion de proveedores',
                'grupo' => 12,
            ],


            //Productos
            [
                // 'id' => 56,
                'nombre' => 'ver-productos',
                'descripcion' => 'Ver listado de productos',
                'grupo' => 13,
            ],
            [
                // 'id' => 57,
                'nombre' => 'crear-productos',
                'descripcion' => 'Crear productos',
                'grupo' => 13,
            ],
            [
                // 'id' => 58,
                'nombre' => 'editar-productos',
                'descripcion' => 'Editar productos',
                'grupo' => 13,
            ],
            [
                // 'id' => 59,
                'nombre' => 'obtener-info-productos',
                'descripcion' => 'Obtener informacion de productos',
                'grupo' => 13,
            ],


            //Lotes
            [
                // 'id' => 60,
                'nombre' => 'ver-lotes',
                'descripcion' => 'Ver listado de lotes',
                'grupo' => 14,
            ],
            [
                // 'id' => 61,
                'nombre' => 'crear-lotes',
                'descripcion' => 'Crear lotes',
                'grupo' => 14,
            ],
            [
                // 'id' => 62,
                'nombre' => 'editar-lotes',
                'descripcion' => 'Editar lotes',
                'grupo' => 14,
            ],
            [
                // 'id' => 63,
                'nombre' => 'obtener-info-lotes',
                'descripcion' => 'Obtener informacion de lotes',
                'grupo' => 14,
            ],

            //Compras
            [
                // 'id' => 64,
                'nombre' => 'ver-compras',
                'descripcion' => 'Ver listado de compras',
                'grupo' => 15,
            ],
            [
                // 'id' => 65,
                'nombre' => 'crear-compras',
                'descripcion' => 'Crear compras',
                'grupo' => 15,
            ],
            [
                // 'id' => 66,
                'nombre' => 'aprobar-compras',
                'descripcion' => 'Aprobar compras',
                'grupo' => 15,
            ],
            [
                // 'id' => 67,
                'nombre' => 'anular-compras',
                'descripcion' => 'Anular compras',
                'grupo' => 15,
            ],
            [
                // 'id' => 68,
                'nombre' => 'obtener-info-compras',
                'descripcion' => 'Obtener informacion de compras',
                'grupo' => 15,
            ],
            [
                // 'id' => 69,
                'nombre' => 'obtener-detalles-compras',
                'descripcion' => 'Obtener detalles de compras',
                'grupo' => 15,
            ],

            //Kardex
            [
                // 'id' => 70,
                'nombre' => 'ver-kardex',
                'descripcion' => 'Ver listado de kardex',
                'grupo' => 16,
            ],
            [
                // 'id' => 71,
                'nombre' => 'crear-kardex',
                'descripcion' => 'Realizar movimientos de kardex',
                'grupo' => 16,
            ],
            [
                // 'id' => 72,
                'nombre' => 'obtener-info-kardex',
                'descripcion' => 'Obtener informacion de kardex',
                'grupo' => 16,
            ],

            //Reportes
            [
                // 'id' => 73,
                'nombre' => 'ver-menu-reportes',
                'descripcion' => 'Ver menú de reportes',
                'grupo' => 17,
            ],
            [
                // 'id' => 74,
                'nombre' => 'generar-reportes',
                'descripcion' => 'Generar reportes',
                'grupo' => 17,
            ],





        ];

        foreach ($permisos as $permiso) {
            permisos::create($permiso);

        }
    }
}
