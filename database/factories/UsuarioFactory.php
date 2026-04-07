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
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName(),
            'correo' => fake()->unique()->safeEmail(),
            'clave' => static::$password ??= Hash::make('password'),
            'rol' => 'cliente', // Por defecto cliente
        ];
    }

    /**
     * Estado para administrador
     */
    public function administrador(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'administrador',
        ]);
    }

    /**
     * Estado para gerente
     */
    public function gerente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'gerente',
        ]);
    }

    /**
     * Estado para cliente
     */
    public function cliente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'cliente',
        ]);
    }
}
