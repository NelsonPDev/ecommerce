<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Electrónica', 'descripcion' => 'Productos electrónicos y gadgets'],
            ['nombre' => 'Ropa', 'descripcion' => 'Ropa y accesorios'],
            ['nombre' => 'Hogar', 'descripcion' => 'Artículos para el hogar'],
            ['nombre' => 'Deportes', 'descripcion' => 'Equipos y accesorios deportivos'],
            ['nombre' => 'Libros', 'descripcion' => 'Libros y material educativo'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
