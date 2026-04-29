<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Categoria::all();
        $vendedores = Usuario::where('es_vendedor', true)->get();

        foreach ($vendedores as $vendedor) {
            foreach (range(1, 3) as $indice) {
                $nombreProducto = 'Producto '.$vendedor->id.'-'.$indice;
                $fotos = $this->crearFotosDemo($nombreProducto);

                $producto = Producto::create([
                    'nombre' => $nombreProducto,
                    'descripcion' => 'Producto de demostracion para pruebas de relaciones, archivos y ventas.',
                    'precio' => fake()->randomFloat(2, 150, 2500),
                    'existencia' => fake()->numberBetween(6, 20),
                    'fotos' => $fotos,
                    'usuario_id' => $vendedor->id,
                ]);

                $producto->categorias()->attach(
                    $categorias->random(fake()->numberBetween(1, min(3, $categorias->count())))->pluck('id')->all()
                );
            }
        }
    }

    protected function crearFotosDemo(string $base): array
    {
        return collect(range(1, fake()->numberBetween(1, 2)))
            ->map(function (int $indice) use ($base) {
                $path = 'productos/'.Str::slug($base).'-'.$indice.'.svg';

                Storage::disk('public')->put($path, $this->svgProducto($base, $indice));

                return $path;
            })
            ->all();
    }

    protected function svgProducto(string $titulo, int $indice): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
  <defs>
    <linearGradient id="bg{$indice}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f172a" />
      <stop offset="100%" stop-color="#0891b2" />
    </linearGradient>
  </defs>
  <rect width="800" height="600" fill="url(#bg{$indice})" rx="32" />
  <circle cx="640" cy="120" r="80" fill="#22d3ee" opacity="0.35" />
  <circle cx="170" cy="470" r="110" fill="#f59e0b" opacity="0.25" />
  <text x="60" y="260" fill="#ffffff" font-family="Arial, sans-serif" font-size="42" font-weight="700">{$titulo}</text>
  <text x="60" y="320" fill="#cffafe" font-family="Arial, sans-serif" font-size="26">Galeria publica {$indice}</text>
</svg>
SVG;
    }
}
