<?php

namespace Database\Seeders;

use App\Models\permisos;
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

        $permisos = permisos::all()->count();

        for ($i = 1; $i <= $permisos; $i++) {
            $rolPermiso = [
                'rol' => 1,
                'permiso' => $i,
            ];
            RolPermisoFactory::new()->create($rolPermiso);
        }
    }
}
