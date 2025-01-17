<?php

namespace Database\Seeders;

use Database\Factories\EmpresaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            [
                'nombre' => 'Empresa General',
                'direccion' => 'Empresa General',
                'telefono ' => '123456789',
                'email' => '-',
                'logo' => '-',
                'NIT' => '123123123123',
                'NRC' => '123123123123',
                'giro' => 'General',
                'representante' => 'Representante',
                'dui' => '123456789',
                'nit_representante' => '123-12121212-1212-1212-1',
                'telefono_representante' => '123456789',
                'email_representante' => '-',
            ]
        ];

        foreach ($empresas as $empresa) {
            EmpresaFactory::new()->create($empresa);
        }
    }
}
