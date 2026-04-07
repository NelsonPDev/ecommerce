<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

            <!-- Correo -->
            <div>
                <x-input-label for="correo" value="Correo" />
                <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('correo')" class="mt-2" />
            </div>

            <!-- Contraseña -->
            <div class="mt-4">
                <x-input-label for="clave" value="Contraseña" />

                <x-text-input id="clave" class="block mt-1 w-full"
                                type="password"
                                name="clave"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('clave')" class="mt-2" />
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordar') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Olvidaste la Contraseña?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Iniciar Secion') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
