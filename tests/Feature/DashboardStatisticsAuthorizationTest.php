<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatisticsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_administrator_receives_statistics_data_in_dashboard_view(): void
    {
        $administrador = Usuario::factory()->administrador()->vendedor()->create();
        $cliente = Usuario::factory()->cliente()->create();

        $respuestaAdministrador = $this->actingAs($administrador)->get(route('dashboard'));
        $datosAdministrador = $respuestaAdministrador->original->getData();

        $respuestaAdministrador->assertOk();
        $this->assertArrayHasKey('totalUsuarios', $datosAdministrador);
        $this->assertArrayHasKey('productosPorCategoria', $datosAdministrador);

        $respuestaCliente = $this->actingAs($cliente)->get(route('dashboard'));
        $datosCliente = $respuestaCliente->original->getData();

        $respuestaCliente->assertOk();
        $this->assertArrayNotHasKey('totalUsuarios', $datosCliente);
        $this->assertArrayNotHasKey('productosPorCategoria', $datosCliente);
    }
}
