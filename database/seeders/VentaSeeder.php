<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Seeder;

class VentaSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = Usuario::where('rol', 'cliente')->first();
        $producto = Producto::where('existencia', '>=', 2)->first();

        if (! $cliente || ! $producto) {
            return;
        }

        Venta::create([
            'producto_id' => $producto->id,
            'vendedor_id' => $producto->usuario_id,
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad' => 2,
            'total' => $producto->precio * 2,
        ]);

        $producto->decrement('existencia', 2);
    }
}
