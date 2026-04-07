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
        // Crear usuarios con roles diferentes
        $admin = Usuario::create([
            'nombre' => 'Admin',
            'apellidos' => 'Sistema',
            'correo' => 'admin@ecommerce.com',
            'clave' => Hash::make('password'),
            'rol' => 'administrador',
        ]);

        $gerente = Usuario::create([
            'nombre' => 'Gerente',
            'apellidos' => 'Principal',
            'correo' => 'gerente@ecommerce.com',
            'clave' => Hash::make('password'),
            'rol' => 'gerente',
        ]);

        $cliente = Usuario::create([
            'nombre' => 'Cliente',
            'apellidos' => 'Ejemplo',
            'correo' => 'cliente@ecommerce.com',
            'clave' => Hash::make('password'),
            'rol' => 'cliente',
        ]);

        // Crear más clientes con factory
        Usuario::factory(5)
            ->cliente()
            ->create();

        // Crear categorías
        $categorias = Categoria::factory(5)->create();

        // Crear productos
        $productos = Producto::factory(15)
            ->recycle($categorias)
            ->create();

        // Crear ventas
        Venta::factory(20)->create();
    }
}