<?php

namespace Database\Seeders;

use App\Models\proveedores;
use Database\Factories\ProveedoresFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Proveedor 1',
                'direccion' => 'Dirección del proveedor 1',
                'telefonoPrincipal' => '123456789',
                'emailPrincipal' => 'proveedor@correo.com',
                'NIT' => '123456-7',
                'representante' => 'Representante 1',
                'telefonoRepresentante' => '987654321',
                'emailRepresentante' => 'representante@representante.com',
                'estado' => 1,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            proveedores::create($proveedor);
        }
    }
}
