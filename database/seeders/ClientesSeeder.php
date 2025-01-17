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
            [
                'nombre' => 'Cliente 1',
                'apellido' => 'Apellido 1',
                'telefono' => '12345678',
                'email' => '-',
                'DUI' => '12345678-9',
                'descuento' => 0,
                'estado' => 1,
            ],
            [
                'nombre' => 'Cliente 2',
                'apellido' => 'Apellido 2',
                'telefono' => '12345678',
                'email' => '-',
                'DUI' => '12345678-9',
                'descuento' => 0,
                'estado' => 1,
            ],
            [
                'nombre' => 'Cliente 3',
                'apellido' => 'Apellido 3',
                'telefono' => '12345678',
                'email' => '-',
                'DUI' => '12345678-9',
                'descuento' => 0,
                'estado' => 1,
            ],
            [
                'nombre' => 'Cliente 4',
                'apellido' => 'Apellido 4',
                'telefono' => '12345678',
                'email' => '-',
                'DUI' => '12345678-9',
                'descuento' => 0,
                'estado' => 1,
            ],

        ];

        foreach ($clientes as $cliente) {
            ClientesFactory::new()->create($cliente);
        }
    }
}
