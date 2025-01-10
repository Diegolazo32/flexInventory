<?php

namespace Database\Seeders;

use Database\Factories\RolPermisoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for($i = 1; $i <= 100; $i++) {
            $rolPermiso = [
                'rol' => 1,
                'permiso' => $i,
            ];
            RolPermisoFactory::new()->create($rolPermiso);
        }
    }
}
