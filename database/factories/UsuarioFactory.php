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
        $nombres = ['Juan', 'Mario', 'Maria', 'Pedro'];
        $apellidos = ['Lopez', 'Sanchez', 'Hernandez', 'Martinez'];

        $nombre = $this->faker->randomElement($nombres);
        $apellido = $this->faker->randomElement($apellidos);

        return [
            'nombre' => $nombre,
            'apellidos' => $apellido,
            'correo' => strtolower(substr($nombre, 0, 1) . $apellido) . '@tuxtla.tecnm.mx',
            'clave' => Hash::make('123'),
            'rol' => $this->faker->randomElement(['cliente', 'gerente']),
        ];
    }
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
