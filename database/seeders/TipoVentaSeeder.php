<?php

namespace Database\Seeders;

use App\Models\tipoVenta;
use Database\Factories\TipoVentaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoVentas = [
            [
                'descripcion' => 'Normal',
            ],
            [
                'descripcion' => 'Descuento',
            ],
            [
                'descripcion' => 'Especial',
            ],
        ];

        foreach ($tipoVentas as $tipoVenta) {
            tipoVenta::create($tipoVenta);
        }
    }
}
