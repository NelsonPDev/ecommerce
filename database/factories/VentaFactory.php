<?php

namespace Database\Factories;

use App\Models\Venta;
use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venta>
 */
class VentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cantidad = $this->faker->numberBetween(1, 10);
        $precio_unitario = $this->faker->randomFloat(2, 10, 500);
        $total = $cantidad * $precio_unitario;

        return [
            'usuario_id' => Usuario::factory()->cliente(),
            'producto_id' => Producto::factory(),
            'cantidad' => $cantidad,
            'precio_unitario' => $precio_unitario,
            'total' => $total,
            'estado' => $this->faker->randomElement(['pendiente', 'completada', 'cancelada']),
        ];
    }
}
