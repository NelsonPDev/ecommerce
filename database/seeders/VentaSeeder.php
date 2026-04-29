<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class VentaSeeder extends Seeder
{
    public function run(): void
    {
        $compradores = Usuario::where('rol', 'cliente')->where('es_vendedor', false)->get();
        $productos = Producto::with('usuario')->get();
        $gerente = Usuario::where('rol', 'gerente')->first();

        foreach ($productos as $producto) {
            $ventasARegistrar = fake()->numberBetween(1, 2);

            foreach (range(1, $ventasARegistrar) as $indice) {
                if ($producto->existencia < 1) {
                    continue;
                }

                $cantidad = fake()->numberBetween(1, min(2, $producto->existencia));
                $comprador = $compradores->random();
                $validada = fake()->boolean(70);
                $ticketPath = $this->crearTicketDemo($producto->id, $indice);

                Venta::create([
                    'producto_id' => $producto->id,
                    'vendedor_id' => $producto->usuario_id,
                    'cliente_id' => $comprador->id,
                    'fecha' => fake()->dateTimeBetween('-30 days')->format('Y-m-d'),
                    'cantidad' => $cantidad,
                    'total' => $producto->precio * $cantidad,
                    'ticket' => $ticketPath,
                    'estado' => $validada ? 'validada' : 'pendiente',
                    'validada_at' => $validada ? now()->subDays(fake()->numberBetween(0, 15)) : null,
                    'validada_por' => $validada ? $gerente?->id : null,
                ]);

                $producto->decrement('existencia', $cantidad);
            }
        }
    }

    protected function crearTicketDemo(int $productoId, int $indice): string
    {
        $path = 'tickets/ticket-'.$productoId.'-'.$indice.'-'.fake()->unique()->numberBetween(1000, 9999).'.svg';

        Storage::disk('private')->put($path, <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="600" viewBox="0 0 900 600">
  <rect width="900" height="600" fill="#ffffff" rx="24" />
  <rect x="30" y="30" width="840" height="540" fill="#f8fafc" stroke="#cbd5e1" stroke-width="4" rx="18" />
  <text x="80" y="120" fill="#0f172a" font-family="Arial, sans-serif" font-size="42" font-weight="700">Ticket privado de venta</text>
  <text x="80" y="220" fill="#334155" font-family="Arial, sans-serif" font-size="28">Producto ID: {$productoId}</text>
  <text x="80" y="280" fill="#334155" font-family="Arial, sans-serif" font-size="28">Referencia: {$indice}</text>
  <text x="80" y="360" fill="#0891b2" font-family="Arial, sans-serif" font-size="30">Uso academico - almacenamiento privado</text>
</svg>
SVG);

        return $path;
    }
}
