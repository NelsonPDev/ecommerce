<?php

namespace Tests\Feature;

use App\Mail\CodigoVerificacionMail;
use App\Models\CodigoVerificacion;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_validate_two_factor_code_before_login(): void
    {
        Mail::fake();

        $usuario = Usuario::factory()->create([
            'correo' => 'cliente@example.com',
            'clave' => 'secreto123',
        ]);

        $this->post(route('login.post'), [
            'correo' => $usuario->correo,
            'clave' => 'secreto123',
        ])->assertRedirect(route('two-factor.show'));

        $this->assertGuest();
        $this->assertDatabaseCount('codigos_verificacion', 1);
        Mail::assertSent(CodigoVerificacionMail::class);

        $codigo = CodigoVerificacion::first();

        $this->post(route('two-factor.verify'), [
            'codigo' => $codigo->codigo,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($usuario);
        $this->assertDatabaseCount('codigos_verificacion', 0);
    }

    public function test_invalid_two_factor_code_does_not_authenticate_user(): void
    {
        Mail::fake();

        $usuario = Usuario::factory()->create([
            'correo' => 'cliente2@example.com',
            'clave' => 'secreto123',
        ]);

        $this->post(route('login.post'), [
            'correo' => $usuario->correo,
            'clave' => 'secreto123',
        ]);

        $this->post(route('two-factor.verify'), [
            'codigo' => '000000',
        ])->assertSessionHasErrors('codigo');

        $this->assertGuest();
    }

    public function test_expired_two_factor_code_does_not_authenticate_user(): void
    {
        Mail::fake();

        $usuario = Usuario::factory()->create([
            'correo' => 'cliente3@example.com',
            'clave' => 'secreto123',
        ]);

        $this->post(route('login.post'), [
            'correo' => $usuario->correo,
            'clave' => 'secreto123',
        ])->assertRedirect(route('two-factor.show'));

        $codigo = CodigoVerificacion::firstOrFail();
        Carbon::setTestNow($codigo->expiracion->copy()->addSecond());

        $this->post(route('two-factor.verify'), [
            'codigo' => $codigo->codigo,
        ])->assertRedirect(route('login'))
            ->assertSessionHasErrors('codigo');

        Carbon::setTestNow();

        $this->assertGuest();
        $this->assertDatabaseCount('codigos_verificacion', 0);
    }
}
