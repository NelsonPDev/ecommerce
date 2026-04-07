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

    protected static int $sequence = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = ['Juan', 'Mario', 'Maria', 'Pedro'];
        $apellidos = ['Lopez', 'Sanchez', 'Hernandez', 'Martinez'];

        $combinaciones = [];

        foreach ($nombres as $nombreBase) {
            foreach ($apellidos as $apellidoBase) {
                $correo = strtolower(substr($nombreBase, 0, 1) . $apellidoBase) . '@tuxtla.tecnm.mx';

                if (in_array($correo, [
                    'jlopez@tuxtla.tecnm.mx',
                    'mmartinez@tuxtla.tecnm.mx',
                    'psanchez@tuxtla.tecnm.mx',
                ], true)) {
                    continue;
                }

                $combinaciones[] = [$nombreBase, $apellidoBase];
            }
        }

        [$nombre, $apellido] = $combinaciones[self::$sequence % count($combinaciones)];
        self::$sequence++;

        return [
            'nombre' => $nombre,
            'apellidos' => $apellido,
            'correo' => strtolower(substr($nombre, 0, 1) . $apellido) . '@tuxtla.tecnm.mx',
            'clave' => Hash::make('123'),
            'rol' => $this->faker->randomElement(['cliente', 'gerente']),
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
