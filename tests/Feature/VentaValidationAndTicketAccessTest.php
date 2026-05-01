<?php

namespace Tests\Feature;

use App\Mail\VentaValidadaCompradorMail;
use App\Mail\VentaValidadaVendedorMail;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VentaValidationAndTicketAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_buyer_or_manager_can_view_private_ticket(): void
    {
        $gerente = Usuario::factory()->gerente()->create();
        $vendedor = Usuario::factory()->cliente()->vendedor()->create();
        $cliente = Usuario::factory()->cliente()->create();
        $intruso = Usuario::factory()->cliente()->create();
        $producto = Producto::factory()->create(['usuario_id' => $vendedor->id]);
        $ticketPath = 'tickets/prueba-'.uniqid().'.png';

        Storage::disk('private')->put($ticketPath, 'contenido');

        $venta = Venta::create([
            'producto_id' => $producto->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad' => 1,
            'total' => 100,
            'ticket' => $ticketPath,
            'estado' => 'pendiente',
        ]);

        $this->actingAs($cliente)->get(route('ventas.ticket', $venta))->assertOk();
        $this->actingAs($gerente)->get(route('ventas.ticket', $venta))->assertOk();
        $this->actingAs($vendedor)->get(route('ventas.ticket', $venta))->assertForbidden();
        $this->actingAs($intruso)->get(route('ventas.ticket', $venta))->assertForbidden();

        Storage::disk('private')->delete($ticketPath);
    }

    public function test_manager_can_validate_sale_and_send_notifications(): void
    {
        Mail::fake();

        $gerente = Usuario::factory()->gerente()->create();
        $vendedor = Usuario::factory()->cliente()->vendedor()->create();
        $cliente = Usuario::factory()->cliente()->create();
        $producto = Producto::factory()->create(['usuario_id' => $vendedor->id]);

        $venta = Venta::create([
            'producto_id' => $producto->id,
            'vendedor_id' => $vendedor->id,
            'cliente_id' => $cliente->id,
            'fecha' => now()->toDateString(),
            'cantidad' => 1,
            'total' => 100,
            'estado' => 'pendiente',
        ]);

        $this->actingAs($gerente)
            ->post(route('ventas.validate', $venta))
            ->assertSessionHas('success');

        $venta->refresh();

        $this->assertSame('validada', $venta->estado);
        $this->assertSame($gerente->id, $venta->validada_por);
        Mail::assertSent(VentaValidadaVendedorMail::class);
        Mail::assertSent(VentaValidadaCompradorMail::class);
    }
}
