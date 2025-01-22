<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lotes = [[
            'codigo' => 'L-0001',
            'numero' => 1,
            'cantidad' => 100,
            'fechaVencimiento' => '2025-01-20',
            'producto' => 1,
            'estado' => 1,
            'inventario' => 1,
        ]];
    }
}
