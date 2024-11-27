<?php

namespace Database\Seeders;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoriaFactory::new()->create([
            'descripcion' => 'General',
        ]);

    }
}
