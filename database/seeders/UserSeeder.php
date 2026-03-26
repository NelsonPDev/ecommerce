<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Crear usuario Gerente
        User::create([
            'name' => 'Juan García',
            'email' => 'gerente@techstore.com',
            'password' => Hash::make('password123'),
            'role' => 'gerente',
            'email_verified_at' => now(),
        ]);

        // Crear usuario Empleado
        User::create([
            'name' => 'María López',
            'email' => 'empleado@techstore.com',
            'password' => Hash::make('password123'),
            'role' => 'empleado',
            'email_verified_at' => now(),
        ]);

        // Crear usuario Cliente
        User::create([
            'name' => 'Carlos Martínez',
            'email' => 'cliente@techstore.com',
            'password' => Hash::make('password123'),
            'role' => 'cliente',
            'email_verified_at' => now(),
        ]);

        // Crear más clientes adicionales
        User::create([
            'name' => 'Ana Rodríguez',
            'email' => 'ana@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'cliente',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pedro Sánchez',
            'email' => 'pedro@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'cliente',
            'email_verified_at' => now(),
        ]);
    }
}
