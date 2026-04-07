<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategoriaSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}