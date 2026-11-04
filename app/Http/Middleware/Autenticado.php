<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Equivalente al filtro 'auth' de app/filters.php (Laravel 4):
 * si no hay sesión válida redirige a /login/ con el mensaje de error.
 */
class Autenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect('/login/')->with('message_error', 'Inicie sesión por favor');
        }

        return $next($request);
    }
}
