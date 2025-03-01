<?php

namespace Database\Seeders;

use App\Models\empresa;
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
                'nombre' => 'Flex Inventory',
                'direccion' => 'Direccion',
                'telefono' => '0000-0000',
                'email' => 'correo@correo.com',
                'logo' => 'logo/empresa_logo.jpg',
                'NIT' => '0000-000000-000-0',
                'NRC' => '0000',
                'giro' => 'General',
                'representante' => '-',
                'dui' => '00000000-0',
                'nit_representante' => '0000-000000-000-0',
                'telefono_representante' => '0000-0000',
                'email_representante' => 'correo@@correo.com',
                'cuentaContable' => '0000-0000-0000-0000',
                'valorIVA' => 13,
                'firstTime' => true,
            ]
        ];

        foreach ($empresas as $empresa) {
            empresa::create($empresa);
        }
    }
}
