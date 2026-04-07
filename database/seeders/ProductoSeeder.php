<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Laptop Gaming',
                'descripcion' => 'Laptop de alta gama para gaming',
                'precio' => 1500.00,
                'existencia' => 10,
                'usuario_id' => Usuario::where('rol', 'administrador')->first()->id,
                'categorias' => ['Electrónica'],
            ],
            [
                'nombre' => 'Camiseta Deportiva',
                'descripcion' => 'Camiseta cómoda para actividades deportivas',
                'precio' => 25.00,
                'existencia' => 50,
                'usuario_id' => Usuario::where('rol', 'gerente')->first()->id,
                'categorias' => ['Ropa', 'Deportes'],
            ],
            [
                'nombre' => 'Libro de Programación',
                'descripcion' => 'Guía completa de Laravel',
                'precio' => 40.00,
                'existencia' => 20,
                'usuario_id' => Usuario::where('rol', 'cliente')->first()->id,
                'categorias' => ['Libros'],
            ],
        ];

        foreach ($productos as $data) {
            $categorias = $data['categorias'];
            unset($data['categorias']);
            $producto = Producto::create($data);
            $categoriaIds = Categoria::whereIn('nombre', $categorias)->pluck('id');
            $producto->categorias()->attach($categoriaIds);
        }
    }
}
