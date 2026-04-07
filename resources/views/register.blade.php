<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-amber-50 text-slate-900">
    <div class="mx-auto flex min-h-screen max-w-5xl items-center justify-center px-4 py-10">
        <div class="w-full rounded-3xl border border-amber-200 bg-white p-8 shadow-xl sm:p-12">
            <div class="mb-8 max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-600">Nuevo cliente</p>
                <h1 class="mt-3 text-3xl font-bold">Crea tu cuenta para comprar productos.</h1>
                <p class="mt-2 text-sm text-slate-500">El registro publico crea usuarios con rol cliente; administradores y gerentes se gestionan desde el modulo de usuarios.</p>
            </div>

            @include('partials.flash')

            <form method="POST" action="{{ route('register.post') }}" class="grid gap-5 md:grid-cols-2">
                @csrf
                <div>
                    <label for="nombre" class="mb-2 block text-sm font-semibold">Nombre</label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none" required>
                </div>
                <div>
                    <label for="apellidos" class="mb-2 block text-sm font-semibold">Apellidos</label>
                    <input id="apellidos" name="apellidos" type="text" value="{{ old('apellidos') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none" required>
                </div>
                <div class="md:col-span-2">
                    <label for="correo" class="mb-2 block text-sm font-semibold">Correo</label>
                    <input id="correo" name="correo" type="email" value="{{ old('correo') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none" required>
                </div>
                <div>
                    <label for="clave" class="mb-2 block text-sm font-semibold">Clave</label>
                    <input id="clave" name="clave" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none" required>
                </div>
                <div>
                    <label for="clave_confirmation" class="mb-2 block text-sm font-semibold">Confirmar clave</label>
                    <input id="clave_confirmation" name="clave_confirmation" type="password" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-slate-900 focus:outline-none" required>
                </div>
                <div class="md:col-span-2 flex items-center justify-between gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Ya tengo cuenta</a>
                    <button type="submit" class="rounded-xl px-6 py-3 text-sm font-semibold hover:bg-amber-600" style="background-color: #0f172a; color: #ffffff; border: 1px solid #0f172a;">
                        Crear cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
