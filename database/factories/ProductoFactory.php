<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->words(2, true),
            'descripcion' => $this->faker->sentence(12),
            'precio' => $this->faker->randomFloat(2, 10, 5000),
            'existencia' => $this->faker->numberBetween(1, 50),
            'usuario_id' => Usuario::factory()->gerente(),
        ];
    }
}
