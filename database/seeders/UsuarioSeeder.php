<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Juan',
            'apellidos' => 'Lopez',
            'correo' => 'jlopez@tuxtla.tecnm.mx',
            'clave' => Hash::make('123'),
            'rol' => 'cliente',
            'es_vendedor' => false,
        ]);

        Usuario::create([
            'nombre' => 'Maria',
            'apellidos' => 'Martinez',
            'correo' => 'mmartinez@tuxtla.tecnm.mx',
            'clave' => Hash::make('123'),
            'rol' => 'gerente',
            'es_vendedor' => true,
        ]);

        Usuario::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Sanchez',
            'correo' => 'psanchez@tuxtla.tecnm.mx',
            'clave' => Hash::make('123'),
            'rol' => 'administrador',
            'es_vendedor' => true,
        ]);

        Usuario::factory(28)->cliente()->vendedor()->create();
        Usuario::factory(69)->cliente()->create();
    }
}
