<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario Administrador
        Usuario::create([
            'nombre' => 'Admin',
            'apellidos' => 'Sistema',
            'correo' => 'admin@admin.com',
            'clave' => Hash::make('123456'),
            'rol' => 'administrador',
            'correo_verified_at' => now(),
        ]);

        // Crear usuario Gerente
        Usuario::create([
            'nombre' => 'Juan',
            'apellidos' => 'García',
            'correo' => 'gerente@gerente.com',
            'clave' => Hash::make('123456'),
            'rol' => 'gerente',
            'correo_verified_at' => now(),
        ]);

        // Crear usuario Cliente
        Usuario::create([
            'nombre' => 'María',
            'apellidos' => 'López',
            'correo' => 'cliente@cliente.com',
            'clave' => Hash::make('123456'),
            'rol' => 'cliente',
            'correo_verified_at' => now(),
        ]);

        // Crear más clientes adicionales
        Usuario::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Martínez',
            'correo' => 'carlos@carlos.com',
            'clave' => Hash::make('123456'),
            'rol' => 'cliente',
            'correo_verified_at' => now(),
        ]);

        Usuario::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Sánchez',
            'correo' => 'pedro@pedro.com',
            'clave' => Hash::make('123456'),
            'rol' => 'cliente',
            'correo_verified_at' => now(),
        ]);
    }
}
