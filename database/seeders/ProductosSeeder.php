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
        $productos = [
            [
                'codigo' => '4578913564',
                'nombre' => 'Pizza',
                'descripcion' => 'Pizza de pepperoni',
                'precioCompra' => 10.00,
                'precioVenta' => 150.00,
                'stock' => 100,
                'stockInicial' => 100,
                'categoria' => 1,
                'tipoVenta' => 1,
                'proveedor' => 1,
                'unidad' => 1,
                'estado' => 1,
            ],
            [
                'codigo' => '68498165185169',
                'nombre' => 'Toallas humedas',
                'descripcion' => 'Toallas humedas para bebe',
                'precioCompra' => 20.00,
                'precioVenta' => 150.00,
                'stock' => 200,
                'stockInicial' => 200,
                'categoria' => 1,
                'tipoVenta' => 1,
                'proveedor' => 1,
                'unidad' => 1,
                'estado' => 1,
            ],
            [
                'codigo' => '984168541684',
                'nombre' => 'Cafe Juan Valdez',
                'descripcion' => 'Cafe Juan Valdez Organico',
                'precioCompra' => 100.00,
                'precioVenta' => 150.00,
                'stock' => 50,
                'stockInicial' => 50,
                'categoria' => 1,
                'tipoVenta' => 1,
                'proveedor' => 1,
                'unidad' => 1,
                'estado' => 1,
            ]

        ];

        foreach ($productos as $producto) {
            ProductosFactory::new()->create($producto);
        }
    }
}
