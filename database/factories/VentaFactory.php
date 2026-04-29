<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venta>
 */
class VentaFactory extends Factory
{
    public function definition(): array
    {
        $producto = Producto::factory()->create();
        $cantidad = $this->faker->numberBetween(1, 3);

        return [
            'producto_id' => $producto->id,
            'vendedor_id' => $producto->usuario_id,
            'cliente_id' => Usuario::factory()->cliente(),
            'fecha' => $this->faker->date(),
            'cantidad' => $cantidad,
            'total' => $producto->precio * $cantidad,
            'ticket' => null,
            'estado' => 'pendiente',
            'validada_at' => null,
            'validada_por' => null,
        ];
    }
}
