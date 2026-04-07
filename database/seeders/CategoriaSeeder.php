<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Electronica', 'descripcion' => 'Productos electronicos y gadgets.'],
            ['nombre' => 'Ropa', 'descripcion' => 'Ropa y accesorios para uso diario.'],
            ['nombre' => 'Hogar', 'descripcion' => 'Articulos utiles para el hogar.'],
            ['nombre' => 'Deportes', 'descripcion' => 'Equipos y accesorios deportivos.'],
            ['nombre' => 'Libros', 'descripcion' => 'Libros y material educativo.'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
