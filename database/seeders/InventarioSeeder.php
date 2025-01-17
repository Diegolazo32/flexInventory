<?php

namespace Database\Seeders;

use App\Models\productos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $productos = productos::count();
        $stock = productos::sum('stock');

        $inventarios = [[
            'fechaApertura' => now(),
            'fechaCierre' => null,
            'ProductosApertura' => $productos,
            'StockApertura' => $stock,
            'ProductosCierre' => 0,
            'StockCierre' => 0,
            'totalInventario' => 0,
            'aperturadoPor' => 1,
            'cerradoPor' => 1,
            'estado' => 3,
        ]];

        foreach ($inventarios as $inventario) {
            \App\Models\Inventario::create($inventario);
        }
    }
}
