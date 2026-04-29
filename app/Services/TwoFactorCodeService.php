<?php

namespace App\Services;

use App\Models\CodigoVerificacion;
use App\Models\Usuario;
use Illuminate\Support\Carbon;

class TwoFactorCodeService
{
    public const EXPIRATION_MINUTES = 5;

    public function generateFor(Usuario $usuario): CodigoVerificacion
    {
        $this->clearFor($usuario);

        return $usuario->codigosVerificacion()->create([
            'codigo' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expiracion' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);
    }

    public function verify(Usuario $usuario, string $codigo): string
    {
        /** @var CodigoVerificacion|null $registro */
        $registro = $usuario->codigosVerificacion()->latest()->first();

        if (! $registro || $registro->codigo !== $codigo) {
            return 'invalid';
        }

        if (Carbon::now()->greaterThan($registro->expiracion)) {
            $registro->delete();

            return 'expired';
        }

        $registro->delete();

        return 'valid';
    }

    public function clearFor(Usuario $usuario): void
    {
        $usuario->codigosVerificacion()->delete();
    }
}
