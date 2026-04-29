<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName().' '.fake()->lastName(),
            'correo' => fake()->unique()->safeEmail(),
            'clave' => Hash::make('123'),
            'rol' => 'cliente',
            'es_vendedor' => false,
        ];
    }

    public function administrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'administrador',
        ]);
    }

    public function gerente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'gerente',
        ]);
    }

    public function cliente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'cliente',
        ]);
    }

    public function vendedor(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_vendedor' => true,
        ]);
    }
}
