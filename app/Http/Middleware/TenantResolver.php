<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pour l'authentification Passport
use Symfony\Component\HttpFoundation\Response;

class TenantResolver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Authentifier le jeton Passport via le guard global
        if (!Auth::guard('api_global')->check()) {
            # code...
            return response()->json(['message' => 'Unauthenticated(global).'], 401);
        }

        // 2. Récupérer l'utilisateur global authentifié
        $utilisateurGlobal = Auth::guard('api_global')->user();

        // 3. Récupérer l'entreprise associée
        $entreprise = $utilisateurGlobal->entreprise;

        if (!$entreprise) {
            # code...
            return response()->json(['message' => 'Tenant not found for this user.'], 401);
        }

        // 4. Configurer dynamiquement la connexion à la DB du tenant
        EnterPrise::setTenantDbConnection($entreprise);

        // OPTIONNEL MAIS RECOMMANDÉ : Re-authentifier l'utilisateur dans le contexte du tenant
        // Ceci permet de s'assurer que l'utilisateur existe dans la DB du tenant et d'y appliquer les rôles/permissions.
        // Vous pouvez passer l'ID de l'utilisateur du tenant dans les claims du jeton global
        // Ou bien, l'authentification réelle de l'utilisateur tenant peut se faire juste après le login-global
        // avec un second appel à un endpoint spécifique au tenant pour obtenir un TOKEN DE TENANT.
        
        // Pour l'instant, nous allons juste configurer la connexion et laisser les contrôleurs du tenant
        // gérer l'authentification interne si nécessaire via un autre guard ou directement.
        
        // Stocker l'entreprise courante dans un service ou via un contexte
        app()->instance('currentTenant', $entreprise);


        return $next($request);
    }
}
