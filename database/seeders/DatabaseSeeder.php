<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            EstadosSeeder::class,
            RolesSeeder::class,
            TipoVentaSeeder::class,
            UnidadesSeeder::class,
            CajaSeeder::class,
            CategoriaSeeder::class,
            ProveedoresSeeder::class,
            ProductosSeeder::class,
            ClientesSeeder::class,
            UsersSeeder::class,
            GruposSeeder::class,
            PermisosSeeder::class,
            //InventarioSeeder::class,
            RolPermisoSeeder::class,
        ]);

    }
}
