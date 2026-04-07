<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Usuario::where('rol', 'administrador')->first();
        $gerente = Usuario::where('rol', 'gerente')->first();

        $productos = [
            [
                'nombre' => 'Laptop Gaming',
                'descripcion' => 'Laptop de alto rendimiento para desarrollo y videojuegos.',
                'precio' => 1500.00,
                'existencia' => 10,
                'usuario_id' => $admin?->id,
                'categorias' => ['Electronica'],
            ],
            [
                'nombre' => 'Camiseta Deportiva',
                'descripcion' => 'Camiseta comoda para actividades deportivas.',
                'precio' => 25.00,
                'existencia' => 50,
                'usuario_id' => $gerente?->id ?? $admin?->id,
                'categorias' => ['Ropa', 'Deportes'],
            ],
            [
                'nombre' => 'Libro de Programacion',
                'descripcion' => 'Guia practica para aprender Laravel y PHP.',
                'precio' => 40.00,
                'existencia' => 20,
                'usuario_id' => $gerente?->id ?? $admin?->id,
                'categorias' => ['Libros'],
            ],
        ];

        foreach ($productos as $data) {
            if (! $data['usuario_id']) {
                continue;
            }

            $categorias = $data['categorias'];
            unset($data['categorias']);

            $producto = Producto::create($data);
            $categoriaIds = Categoria::whereIn('nombre', $categorias)->pluck('id');
            $producto->categorias()->attach($categoriaIds);
        }
    }
}
