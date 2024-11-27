<?php

namespace Database\Seeders;

use Database\Factories\ClientesFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientesFactory::new()->create([
            'nombre' => 'Cliente',
            'apellido' => 'General',
            'direccion' => '-',
            'telefono' => '-',
            'email' => '-',
            'fecha_nacimiento' => '2024-01-01',
            'sexo' => 1,
            'estado' => 1,
        ]);
    }
}
