<?php

namespace Database\Seeders;

use Database\Factories\UnidadesFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades =
        [

            [
                'descripcion' => 'Unidad',
            ],
            [
                'descripcion' => 'Kilogramo',
            ],
            [
                'descripcion' => 'Litro',
            ],
            [
                'descripcion' => 'Metro',
            ],
            [
                'descripcion' => 'Pieza',
            ],
            [
                'descripcion' => 'Caja',
            ],
            [
                'descripcion' => 'Bolsa',
            ],
            [
                'descripcion' => 'Paquete',
            ],
            [
                'descripcion' => 'Docena',
            ],
            [
                'descripcion' => 'Botella',
            ],
            [
                'descripcion' => 'Galón',
            ],
            [
                'descripcion' => 'Metro cuadrado',
            ],
            [
                'descripcion' => 'Metro cúbico',
            ],
            [
                'descripcion' => 'Tonelada',
            ],
            [
                'descripcion' => 'Barril',
            ],
            [
                'descripcion' => 'Blister',
            ]

        ];

        foreach ($unidades as $unidad) {
            UnidadesFactory::new()->create($unidad);
        }
    }
}
