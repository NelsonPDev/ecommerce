<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Usuario::create([
            'nombre' => 'Juan',
            'apellidos' => 'Lopez',
            'correo' => 'jlopez@tuxtla.tecnm.mx',
            'clave' => \Illuminate\Support\Facades\Hash::make('123'),
            'rol' => 'cliente',
        ]);

        \App\Models\Usuario::create([
            'nombre' => 'Maria',
            'apellidos' => 'Martinez',
            'correo' => 'mmartinez@tuxtla.tecnm.mx',
            'clave' => \Illuminate\Support\Facades\Hash::make('123'),
            'rol' => 'gerente',
        ]);

        \App\Models\Usuario::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Sanchez',
            'correo' => 'psanchez@tuxtla.tecnm.mx',
            'clave' => \Illuminate\Support\Facades\Hash::make('123'),
            'rol' => 'administrador',
        ]);

        // Crear más con factory
        \App\Models\Usuario::factory(5)->create();
    }
}
