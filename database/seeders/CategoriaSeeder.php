<?php

namespace Database\Seeders;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categorias = [
            [
                'descripcion' => 'General',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Electrodomésticos',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Tecnología',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Hogar',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Deportes',
                'estado' => 1,
            ],
            

        ];

        foreach ($categorias as $categoria) {
            CategoriaFactory::new()->create($categoria);
        }
    }
}
