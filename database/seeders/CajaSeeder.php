<?php

namespace Database\Seeders;

use App\Models\caja;
use Database\Factories\CajaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $caja = [
            [
                'nombre' => 'Caja Principal',
                'ubicacion' => 'Ubicacion Principal',
                'estado' => 1,
            ],
            [
                'nombre' => 'Caja Secundaria',
                'ubicacion' => 'Ubicacion Secundaria',
                'estado' => 1,
            ],
            [
                'nombre' => 'Caja Terciaria',
                'ubicacion' => 'Ubicacion Terciaria',
                'estado' => 1,
            ],
        ];

    }
}
