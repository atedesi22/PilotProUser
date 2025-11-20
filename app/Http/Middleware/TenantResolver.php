<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantService;
use App\Models\UtilisateurGlobal;
use Illuminate\Support\Facades\Log;

class TenantResolver
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Vérifier si un utilisateur global est authentifié
        // Nous utilisons 'web' pour la session classique, ou 'api' pour Passport/Sanctum
        // Assurez-vous que l'authentification globale (via Passport par exemple) est déjà passée.
        // Ici, nous nous basons sur l'utilisateur déjà authentifié par 'api' (Passport)
        $user = Auth::guard('api')->user();

        if (!$user instanceof UtilisateurGlobal) {
            // Si pas d'utilisateur global authentifié ou si c'est un autre type d'utilisateur,
            // ou si la route n'a pas besoin de résolution de tenant (ex: routes d'authentification globales).
            // Laissez la requête passer, elle utilisera la connexion par défaut ou 'tenant_main'
            // si elle est destinée à des opérations globales.
            Log::debug("No UtilisateurGlobal authenticated or not required for tenant resolution.");
            return $next($request);
        }

        // 2. Récupérer l'entreprise associée à cet utilisateur global
        $entreprise = $user->entreprise; // Assurez-vous que la relation 'entreprise' est définie dans UtilisateurGlobal

        if (!$entreprise) {
            Log::error("Authenticated UtilisateurGlobal {$user->id_utilisateur_global} has no associated Entreprise.");
            return response()->json(['message' => 'Entreprise non trouvée pour cet utilisateur.'], Response::HTTP_UNAUTHORIZED);
        }

        // 3. Configurer la connexion 'tenant' de Laravel pour cette entreprise
        try {
            $this->tenantService->setTenantDbConnection($entreprise);
            
            // Stocker l'entreprise du tenant dans le conteneur de services pour un accès facile
            // depuis d'autres parties de l'application (ex: contrôleurs, services).
            app()->instance('currentTenant', $entreprise);

            Log::debug("Tenant connection resolved for Enterprise: {$entreprise->nom_entreprise} (DB: {$entreprise->db_name})");

        } catch (\Exception $e) {
            Log::error("Failed to set tenant DB connection for entreprise {$entreprise->id_entreprise}: " . $e->getMessage());
            return response()->json(['message' => 'Erreur de connexion à la base de données du tenant.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Continuer le traitement de la requête avec la connexion 'tenant' configurée
        return $next($request);
    }
}