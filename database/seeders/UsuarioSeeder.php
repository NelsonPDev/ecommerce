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


        Usuario::create([
            'nombre' => 'Jose',
            'apellidos' => 'Adrian',
            'correo' => 'liy.jose.adri@gmail.com',
            'clave' => Hash::make('123'),
            'rol' => 'administrador',
            'es_vendedor' => false,
        ]);

        Usuario::create([
            'nombre' => 'Jose',
            'apellidos' => 'Perez',
            'correo' => 'liy.jose.p7@gmail.com',
            'clave' => Hash::make('123'),
            'rol' => 'gerente',
            'es_vendedor' => false,
        ]);

        Usuario::create([
            'nombre' => 'Enrique',
            'apellidos' => 'Lee',
            'correo' => 'glee7kike@gmail.com',
            'clave' => Hash::make('123'),
            'rol' => 'cliente',
            'es_vendedor' => false,
        ]);

        Usuario::factory(28)->cliente()->vendedor()->create();
        Usuario::factory(69)->cliente()->create();
    }
}
