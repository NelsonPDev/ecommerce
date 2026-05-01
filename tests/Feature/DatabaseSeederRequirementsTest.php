<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeederRequirementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_matches_required_distribution_and_relationships(): void
    {
        Storage::fake('public');
        Storage::fake('private');

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('usuarios', 100);
        $this->assertSame(30, \App\Models\Usuario::where('es_vendedor', true)->count());
        $this->assertSame(70, \App\Models\Usuario::where('es_vendedor', false)->count());

        $this->assertSame(
            0,
            \App\Models\Usuario::where('es_vendedor', true)
                ->withCount('productos')
                ->get()
                ->filter(fn ($usuario) => $usuario->productos_count < 3)
                ->count()
        );

        $this->assertSame(
            0,
            \App\Models\Producto::withCount('categorias')
                ->get()
                ->filter(fn ($producto) => $producto->categorias_count < 1)
                ->count()
        );
    }
}
