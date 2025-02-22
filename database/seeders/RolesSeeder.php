<?php

namespace Database\Seeders;

use App\Models\roles;
use Database\Factories\RolesFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'descripcion' => 'Administrador',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Gerente',
                'estado' => 1,
            ],
            [
                'descripcion' => 'Vendedor',
                'estado' => 1,
            ],
        ];

        foreach ($roles as $rol) {
            roles::create($rol);
        }
    }
}
