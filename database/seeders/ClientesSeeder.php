<?php

namespace Database\Seeders;

use App\Models\clientes;
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
        $clientes = [
            [
                'nombre' => 'Cliente General',
                'apellido' => 'Cliente General',
                'telefono' => '-',
                'email' => '-',
                'DUI' => '-',
                'descuento' => 0,
                'estado' => 1,
            ],

        ];

        foreach ($clientes as $cliente) {
           clientes::create($cliente);
        }
    }
}
