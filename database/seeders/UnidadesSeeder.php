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
        $unidades = [

            [
                'descripcion' => 'Unidad',
                'abreviatura' => 'Und',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Kilogramo',
                'abreviatura' => 'Kg',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Litro',
                'abreviatura' => 'Lt',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Metro',
                'abreviatura' => 'M',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Metro cuadrado',
                'abreviatura' => 'M2',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Metro cúbico',
                'abreviatura' => 'M3',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Pieza',
                'abreviatura' => 'Pz',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Caja',
                'abreviatura' => 'Cj',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Bulto',
                'abreviatura' => 'Blt',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Paquete',
                'abreviatura' => 'Pqt',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Saco',
                'abreviatura' => 'Sc',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Lata',
                'abreviatura' => 'Lt',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Frasco',
                'abreviatura' => 'Fco',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Barril',
                'abreviatura' => 'Brrl',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Cilindro',
                'abreviatura' => 'Cil',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Cubo',
                'abreviatura' => 'Cb',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Rollo',
                'abreviatura' => 'Rl',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Bandeja',
                'abreviatura' => 'Bd',
                'estado' => 1,
            ],
        ];

        foreach ($unidades as $unidad) {
            UnidadesFactory::new()->create($unidad);
        }
    }
}
