<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventarios = [[
            'fechaApertura' => '2022-01-01',
            'fechaCierre' => null,
            'ProductosApertura' => 0,
            'ProductosCierre' => 0,
            'estado' => 1,
        ]];

        foreach ($inventarios as $inventario) {
            \App\Models\Inventario::create($inventario);
        }
    }
}
