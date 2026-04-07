<?php

namespace App\Providers;

use App\Models\Usuario;
use App\Models\Producto;
use App\Policies\UsuarioPolicy;
use App\Policies\ProductoPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Usuario::class => UsuarioPolicy::class,
        Producto::class => ProductoPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Definir gates para roles
        Gate::define('es-administrador', function (Usuario $user) {
            return $user->rol === 'administrador';
        });

        Gate::define('es-gerente', function (Usuario $user) {
            return $user->rol === 'gerente';
        });

        Gate::define('es-cliente', function (Usuario $user) {
            return $user->role === 'cliente';
        });
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
