<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Importez la façade Auth
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. On vérifie si l'utilisateur est connecté ET s'il est admin
        // La méthode user() retourne null si l'utilisateur n'est pas connecté.
        if (!Auth::check() || $request->user()->role !== 'admin') {

            // 2. Si ce n'est pas un admin, on le redirige vers la carte
            // avec un message d'erreur.
            return redirect(route('carte.index'))->with('error', 'Accès non autorisé.');
        }

        // 3. Si l'utilisateur est bien un admin, on le laisse continuer sa requête
        return $next($request);
    }
}
