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

        // Lista de productos tecnológicos y sus imágenes (URLs libres de Unsplash)
        $productosTecnologia = [
            [
                'nombre' => 'Laptop Dell XPS 13',
                'descripcion' => 'Laptop ultraligera con pantalla InfinityEdge y procesador Intel Core i7.',
                'imagen_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800',
            ],
            [
                'nombre' => 'Smartphone Samsung Galaxy S24',
                'descripcion' => 'Smartphone de última generación con cámara de alta resolución y pantalla AMOLED.',
                'imagen_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800',
            ],
            [
                'nombre' => 'Tablet Apple iPad Pro',
                'descripcion' => 'Tablet profesional con chip M2 y pantalla Liquid Retina.',
                'imagen_url' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800',
            ],
            [
                'nombre' => 'Monitor LG UltraWide 34"',
                'descripcion' => 'Monitor panorámico para productividad y gaming.',
                'imagen_url' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800',
            ],
            [
                'nombre' => 'Teclado Mecánico Logitech G Pro',
                'descripcion' => 'Teclado mecánico RGB para gamers y programadores.',
                'imagen_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800',
            ],
            [
                'nombre' => 'Mouse Inalámbrico Razer Viper',
                'descripcion' => 'Mouse inalámbrico ultraligero y preciso para eSports.',
                'imagen_url' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800',
            ],
            [
                'nombre' => 'Audífonos Sony WH-1000XM5',
                'descripcion' => 'Audífonos inalámbricos con cancelación de ruido líder en la industria.',
                'imagen_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800',
            ],
            [
                'nombre' => 'Smartwatch Apple Watch Series 9',
                'descripcion' => 'Reloj inteligente con monitoreo de salud y pantalla always-on.',
                'imagen_url' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=800',
            ],
            [
                'nombre' => 'Disco SSD Samsung 980 Pro 1TB',
                'descripcion' => 'SSD NVMe de alto rendimiento para gaming y trabajo profesional.',
                'imagen_url' => 'https://images.unsplash.com/photo-1597852074816-d933c7d2b988?w=800',
            ],
            [
                'nombre' => 'Cámara Web Logitech C920',
                'descripcion' => 'Cámara web Full HD para videollamadas y streaming.',
                'imagen_url' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=800',
            ],
        ];

        $totalProductos = count($productosTecnologia);
        $indiceProducto = 0;
        foreach ($vendedores as $vendedor) {
            foreach (range(1, 3) as $i) {
                $productoInfo = $productosTecnologia[$indiceProducto % $totalProductos];
                $indiceProducto++;

                // Descargar imagen y guardarla localmente
                $nombreArchivo = 'productos/' . \Str::slug($productoInfo['nombre']) . '.jpg';
                $rutaCompleta = storage_path('app/public/' . $nombreArchivo);
                if (!file_exists($rutaCompleta)) {
                    try {
                        $imgData = @file_get_contents($productoInfo['imagen_url']);
                        if ($imgData !== false) {
                            \Storage::disk('public')->put($nombreArchivo, $imgData);
                        } else {
                            // Si falla la descarga, crea un SVG de respaldo
                            \Storage::disk('public')->put($nombreArchivo, self::svgProductoDemo($productoInfo['nombre']));
                        }
                    } catch (\Exception $e) {
                        \Storage::disk('public')->put($nombreArchivo, self::svgProductoDemo($productoInfo['nombre']));
                    }
                }

                $producto = Producto::create([
                    'nombre' => $productoInfo['nombre'],
                    'descripcion' => $productoInfo['descripcion'],
                    'precio' => fake()->randomFloat(2, 150, 2500),
                    'existencia' => fake()->numberBetween(6, 20),
                    'fotos' => [$nombreArchivo],
                    'usuario_id' => $vendedor->id,
                ]);

                $producto->categorias()->attach(
                    $categorias->random(fake()->numberBetween(1, min(3, $categorias->count())))->pluck('id')->all()
                );
            }
        }
    }

        // Método auxiliar para SVG de respaldo si falla la descarga
        protected static function svgProductoDemo(string $titulo): string
        {
                $titulo = htmlspecialchars($titulo);
                return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#0f172a" />
            <stop offset="100%" stop-color="#0891b2" />
        </linearGradient>
    </defs>
    <rect width="800" height="600" fill="url(#bg)" rx="32" />
    <circle cx="640" cy="120" r="80" fill="#22d3ee" opacity="0.35" />
    <circle cx="170" cy="470" r="110" fill="#f59e0b" opacity="0.25" />
    <text x="60" y="260" fill="#ffffff" font-family="Arial, sans-serif" font-size="42" font-weight="700">$titulo</text>
    <text x="60" y="320" fill="#cffafe" font-family="Arial, sans-serif" font-size="26">Imagen demo</text>
</svg>
SVG;
        }
}
