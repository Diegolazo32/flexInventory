<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\clientes>
 */
class ClientesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name,
            'apellido' => $this->faker->name,
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'DUI' => $this->faker->randomNumber(8),
            'descuento' => $this->faker->randomNumber(2),
        ];
    }
}
