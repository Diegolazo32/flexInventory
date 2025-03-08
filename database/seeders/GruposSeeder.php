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
                    'descripcion' => 'Dashboard',
                ],
                [
                    'id' => 2,
                    'descripcion' => 'Empresa',
                ],
                [
                    'id' => 3,
                    'descripcion' => 'Usuarios',
                ],
                [
                    'id' => 4,
                    'descripcion' => 'Roles',
                ],
                [
                    'id' => 5,
                    'descripcion' => 'Permisos',
                ],
                [
                    'id' => 6,
                    'descripcion' => 'Unidades',
                ],
                [
                    'id' => 7,
                    'descripcion' => 'Categorias',
                ],
                [
                    'id' => 8,
                    'descripcion' => 'Cajas',
                ],
                [
                    'id' => 9,
                    'descripcion' => 'Estados',
                ],
                [
                    'id' => 10,
                    'descripcion' => 'Inventario',
                ],
                [
                    'id' => 11,
                    'descripcion' => 'Clientes',
                ],
                [
                    'id' => 12,
                    'descripcion' => 'Proveedores',
                ],
                [
                    'id' => 13,
                    'descripcion' => 'Productos',
                ],
                [
                    'id' => 14,
                    'descripcion' => 'Lotes',
                ],
                [
                    'id' => 15,
                    'descripcion' => 'Compras',
                ],
                [
                    'id' => 16,
                    'descripcion' => 'Kardex',
                ],
                [
                    'id' => 17,
                    'descripcion' => 'Reportes',
                ],
                [
                    'id' => 18,
                    'descripcion' => 'Registros',
                ],
                [
                    'id' => 19,
                    'descripcion' => 'Ventas',
                ],
                [
                    'id' => 20,
                    'descripcion' => 'Turnos',
                ],






            ];

        foreach ($grupos as $grupo) {
            Grupos::create($grupo);
        }
    }
}
