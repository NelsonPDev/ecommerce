<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUsuarioRequest;
use App\Http\Requests\VerifyTwoFactorCodeRequest;
use App\Mail\CodigoVerificacionMail;
use App\Models\Usuario;
use App\Services\TwoFactorCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    private const TWO_FACTOR_SESSION_KEY = 'autenticacion.usuario_2fa';

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function login(LoginUsuarioRequest $request, TwoFactorCodeService $twoFactorCodeService)
    {
        $credentials = [
            'correo' => $request->validated('correo'),
            'password' => $request->validated('clave'),
        ];

        if (Auth::validate($credentials)) {
            $usuario = Usuario::where('correo', $request->validated('correo'))->firstOrFail();

            Log::channel('autenticacion')->info('Login correcto (fase 1)', [
                'usuario_id' => $usuario->id,
                'correo' => $request->validated('correo'),
                'ip' => $request->ip(),
            ]);

            try {
                $codigoVerificacion = $twoFactorCodeService->generateFor($usuario);

                Mail::to($usuario->correo)->send(new CodigoVerificacionMail($usuario, $codigoVerificacion));

                $request->session()->put(self::TWO_FACTOR_SESSION_KEY, $usuario->id);

                Log::channel('autenticacion')->info('Codigo 2FA generado', [
                    'usuario_id' => $usuario->id,
                    'ip' => $request->ip(),
                    'expiracion' => $codigoVerificacion->expiracion->toDateTimeString(),
                ]);

                return redirect()
                    ->route('two-factor.show')
                    ->with('success', 'Te enviamos un codigo de verificacion por correo.');
            } catch (\Throwable $exception) {
                $twoFactorCodeService->clearFor($usuario);
                $request->session()->forget(self::TWO_FACTOR_SESSION_KEY);

                Log::channel('autenticacion')->error('Error al enviar codigo 2FA', [
                    'usuario_id' => $usuario->id,
                    'ip' => $request->ip(),
                    'mensaje' => $exception->getMessage(),
                ]);

                return back()->with('error', 'No fue posible enviar el codigo de verificacion. Intenta nuevamente.');
            }
        }

        Log::channel('autenticacion')->warning('Login fallido', [
            'correo' => $request->validated('correo'),
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('correo');
    }

    public function showTwoFactorForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (! $this->pendingTwoFactorUser($request)) {
            return redirect()->route('login')->with('error', 'Primero debes iniciar el proceso de acceso.');
        }

        return view('auth.two-factor');
    }

    public function verifyTwoFactorCode(VerifyTwoFactorCodeRequest $request, TwoFactorCodeService $twoFactorCodeService)
    {
        $usuario = $this->pendingTwoFactorUser($request);

        if (! $usuario) {
            return redirect()->route('login')->with('error', 'La verificacion ya no esta disponible.');
        }

        $resultado = $twoFactorCodeService->verify($usuario, $request->validated('codigo'));

        if ($resultado === 'expired') {
            Log::channel('autenticacion')->warning('Codigo expirado', [
                'usuario_id' => $usuario->id,
                'ip' => $request->ip(),
            ]);

            $request->session()->forget(self::TWO_FACTOR_SESSION_KEY);

            return redirect()->route('login')->withErrors([
                'codigo' => 'El codigo ha expirado. Inicia sesion nuevamente para recibir otro.',
            ]);
        }

        if ($resultado === 'invalid') {
            Log::channel('autenticacion')->warning('Codigo invalido', [
                'usuario_id' => $usuario->id,
                'ip' => $request->ip(),
            ]);

            return back()->withErrors([
                'codigo' => 'El codigo ingresado no es valido.',
            ]);
        }

        $request->session()->forget(self::TWO_FACTOR_SESSION_KEY);
        Auth::login($usuario);
        $request->session()->regenerate();

        Log::channel('autenticacion')->info('Codigo validado correctamente', [
            'usuario_id' => $usuario->id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Bienvenido al sistema.');
    }

    public function resendTwoFactorCode(Request $request, TwoFactorCodeService $twoFactorCodeService)
    {
        $usuario = $this->pendingTwoFactorUser($request);

        if (! $usuario) {
            return redirect()->route('login')->with('error', 'La verificacion ya no esta disponible.');
        }

        $codigoVerificacion = $twoFactorCodeService->generateFor($usuario);
        Mail::to($usuario->correo)->send(new CodigoVerificacionMail($usuario, $codigoVerificacion));

        Log::channel('autenticacion')->info('Codigo 2FA generado', [
            'usuario_id' => $usuario->id,
            'ip' => $request->ip(),
            'expiracion' => $codigoVerificacion->expiracion->toDateTimeString(),
        ]);

        return back()->with('success', 'Se envio un nuevo codigo de verificacion.');
    }

    public function logout(Request $request)
    {
        Log::channel('autenticacion')->info('Logout', [
            'usuario_id' => Auth::user()?->id,
            'correo' => Auth::user()?->correo,
            'ip' => $request->ip(),
        ]);

        $request->session()->forget(self::TWO_FACTOR_SESSION_KEY);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesion cerrada correctamente.');
    }

    protected function pendingTwoFactorUser(Request $request): ?Usuario
    {
        $usuarioId = $request->session()->get(self::TWO_FACTOR_SESSION_KEY);

        return $usuarioId ? Usuario::find($usuarioId) : null;
    }
}
