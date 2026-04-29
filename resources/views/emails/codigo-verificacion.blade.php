<h1>Verificacion de inicio de sesion</h1>

<p>Hola {{ $usuario->nombre }},</p>

<p>Tu codigo de verificacion es:</p>

<p style="font-size: 28px; font-weight: bold; letter-spacing: 0.3em;">{{ $codigoVerificacion->codigo }}</p>

<p>Este codigo expira el {{ $codigoVerificacion->expiracion->format('Y-m-d H:i') }}.</p>
