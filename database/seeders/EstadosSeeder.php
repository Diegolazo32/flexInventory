<?php

namespace Database\Seeders;

use App\Models\estados;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['descripcion' => 'Activo'],
            ['descripcion' => 'Inactivo'],
            ['descripcion' => 'Abierto'],
            ['descripcion' => 'Cerrado'],
            ['descripcion' => 'Pendiente'],
            ['descripcion' => 'Pagado'],
            ['descripcion' => 'Anulado'],
            ['descripcion' => 'Restaurado'],
            ['descripcion' => 'Libre'],
            ['descripcion' => 'En turno'],
        ];

        foreach ($estados as $estado) {
            estados::create($estado);
        }
    }
}
