<?php

namespace Database\Seeders;

use App\Models\Grupos;
use Database\Factories\GruposFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GruposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grupos =
            [
                [
                    'id' => 1,
                    'descripcion' => 'Usuarios',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'Unidades'
                ],
                [
                    'id' => 3,
                    'descripcion' => 'Roles'
                ],
                [
                    'id' => 4,
                    'descripcion' => 'Permisos'
                ],
                [
                    'id' => 5,
                    'descripcion' => 'Estados'
                ],
                [
                    'id' => 6,
                    'descripcion' => 'Categorias'
                ],
                [
                    'id' => 7,
                    'descripcion' => 'Productos'
                ],
                [
                    'id' => 8,
                    'descripcion' => 'Proveedores'
                ],
                [
                    'id' => 9,
                    'descripcion' => 'Clientes'
                ],
                [
                    'id' => 10,
                    'descripcion' => 'Ventas'
                ],
                [
                    'id' => 11,
                    'descripcion' => 'Tipo de ventas'
                ],
                [
                    'id' => 12,
                    'descripcion' => 'Dashboard'
                ],
                [
                    'id' => 13,
                    'descripcion' => 'Reportes'
                ],
                [
                    'id' => 14,
                    'descripcion' => 'Configuracion'
                ],
                [
                    'id' => 15,
                    'descripcion' => 'Reestablecer contraseña'
                ],

            ];

        foreach ($grupos as $grupo) {
            GruposFactory::new()->create($grupo);
        }
    }
}
