<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('TestAuth middleware ejecutado. Auth check: ' . (auth()->check() ? 'true' : 'false'));
        
        if (!auth()->check()) {
            \Illuminate\Support\Facades\Log::info('Usuario no autenticado, redirigiendo al login');
            return redirect()->route('login');
        }

        \Illuminate\Support\Facades\Log::info('Usuario autenticado, continuando...');
        return $next($request);
    }
}
