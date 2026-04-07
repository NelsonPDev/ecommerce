@php
    $publicRoute = request()->routeIs([
        'home',
        'about',
        'contact',
        'login',
        'register',
        'login.post',
        'register.post',
    ]);
@endphp

<nav class="border-b border-slate-200 bg-white shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-lg font-bold text-slate-900">TechStore</a>

            @auth
                @if (! $publicRoute)
                    <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900">Dashboard</a>

                    @if (auth()->user()->esAdministrador())
                        <a href="{{ route('usuarios.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Usuarios</a>
                        <a href="{{ route('ventas.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Ventas</a>
                    @elseif (auth()->user()->esGerente())
                        <a href="{{ route('usuarios.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Clientes</a>
                        <a href="{{ route('ventas.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Ventas</a>
                    @else
                        <a href="{{ route('ventas.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Mis compras</a>
                    @endif
                @endif
            @endauth
        </div>

        <div class="flex items-center gap-3">
            @auth
                @if ($publicRoute)
                    <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                        Ir al dashboard
                    </a>
                @else
                    <div class="text-right text-sm">
                        <p class="font-semibold text-slate-900">{{ auth()->user()->nombre }} {{ auth()->user()->apellidos }}</p>
                        <p class="text-slate-500">{{ auth()->user()->rol }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                            Cerrar sesion
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Iniciar sesion
                </a>
                <a href="{{ route('register') }}" class="rounded-lg px-4 py-2 text-sm font-semibold hover:bg-slate-700" style="background-color: #0f172a; color: #ffffff; border: 1px solid #0f172a;">
                    Registrarse
                </a>
            @endauth
        </div>
    </div>
</nav>
