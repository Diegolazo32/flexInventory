<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UnidadesFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{

    /*
    Campos de usuario
            'nombre' => 'Admin',
            'apellido' => 'Admin',
            'DUI' =>   '00000000-0',
            'fechaNacimiento' => $this->faker->date(),
            'edad' => $this->faker->numberBetween(18, 65),
            'genero' => 1,
            'usuario' => 'Admin',
            'password' => Hash::make('password'),
            'rol' => 1,
            'estado' => 1,

    */

    public function run()
    {
        $usuario = [
            [
                'nombre' => 'Admin',
                'apellido' => 'Admin',
                'DUI' =>   '00000000-0',
                'fechaNacimiento' => '1999-01-01',
                'edad' => 18,
                'genero' => 1,
                'usuario' => 'Admin',
                'password' => Hash::make('password'),
                'rol' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'User',
                'apellido' => 'User',
                'DUI' =>   '00000000-0',
                'fechaNacimiento' => '1999-01-01',
                'edad' => 18,
                'genero' => 1,
                'usuario' => 'User',
                'password' => Hash::make('password'),
                'rol' => 2,
                'estado' => 1,
            ],
        ];

        foreach ($usuario as $user) {
            User::create($user);
        }
    }
}
