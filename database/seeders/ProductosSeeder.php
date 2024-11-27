<?php

namespace Database\Seeders;

use Database\Factories\ProductosFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductosFactory::new()->create([
            'codigo' => '0001',
            'nombre' => 'Producto 1',
            'descripcion' => 'Descripción del producto 1',
            'precioCompra' => 100.00,
            'precioVenta' => 150.00,
            'stock' => 100,
            'stockInicial' => 100,
            'categoria' => 1,
            'tipoVenta' => 1,
            'proveedor' => 1,
            'unidad' => 1,
            'estado' => 1,
        ]);
    }
}
