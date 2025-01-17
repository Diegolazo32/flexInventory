<?php

namespace Database\Seeders;

use Database\Factories\EstadosFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $estados = [
            [
                'descripcion' => 'Activo',
            ],
            [
                'descripcion' => 'Inactivo',
            ],
            [
                'descripcion' => 'Abierto',
            ],
            [
                'descripcion' => 'Cerrado',
            ],


        ];

        foreach ($estados as $estado) {
            EstadosFactory::new()->create($estado);
        }
    }
}
