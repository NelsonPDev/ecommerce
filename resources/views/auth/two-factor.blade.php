<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificacion 2FA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 bg-[radial-gradient(circle_at_top,#164e63,transparent_55%)] text-slate-100">
    <div class="mx-auto flex min-h-screen max-w-4xl items-center justify-center px-4">
        <div class="w-full rounded-3xl bg-white p-8 shadow-2xl sm:p-12">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Segundo factor</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-900">Ingresa el codigo enviado a tu correo</h1>
            <p class="mt-3 text-slate-600">El codigo es numerico y expira en 5 minutos.</p>

            <div class="mt-6">
                @include('partials.flash')
            </div>

            <form method="POST" action="{{ route('two-factor.verify') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="codigo" class="mb-2 block text-sm font-semibold text-slate-700">Codigo de verificacion</label>
                    <input id="codigo" name="codigo" type="text" inputmode="numeric" maxlength="6" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-center text-2xl tracking-[0.4em] text-slate-900 focus:border-slate-900 focus:outline-none" required autofocus>
                </div>
                <button type="submit" class="w-full rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-cyan-600" style="background-color: #0f172a; color: #ffffff; border: 1px solid #0f172a;">
                    Verificar e ingresar
                </button>
            </form>

            <form method="POST" action="{{ route('two-factor.resend') }}" class="mt-4">
                @csrf
                <button type="submit" class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">
                    Reenviar codigo
                </button>
            </form>
        </div>
    </div>
</body>
</html>
