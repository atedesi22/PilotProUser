<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Gère l'inscription d'une nouvelle entreprise et de son administrateur principal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        // 1. Validation des données d'inscription
        $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:255', 'unique:tenant_main.entreprises,nom_entreprise'],
            'email_admin' => ['required', 'string', 'email', 'max:255', 'unique:tenant_main.utilisateurs_globaux,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Utiliser une transaction pour s'assurer que tout est créé ou rien n'est créé
        DB::connection('tenant_main')->beginTransaction();
        try {
            // 2. Préparer les identifiants de connexion de la DB du tenant
            // Génération d'un nom de base de données unique et sécurisé.
            // Préfixe 'pilotpro_' + UUID pour éviter les collisions et garantir la sécurité.
            $dbName = 'pilotpro_' . Str::uuid()->toString();
            // Pour l'instant, utilisez les identifiants par défaut ou configurez-les
            // selon votre stratégie (ex: un utilisateur DB par tenant).
            // Pour une démo, on peut simplifier, mais en prod il faudrait générer dynamiquement.
            $dbUser = env('DB_TENANT_USERNAME', 'tenant_user');// Utilisateur DB avec des droits sur les DB de tenant
            $dbPassword = env('DB_TENANT_PASSWORD', 'password'); // Mot de passe pour cet utilisateur
            $dbHost = env('DB_HOST', 'localhost');

            // 3. Création de l'Entreprise dans la base de données globale
            $entreprise = Entreprise::create([
                'nom_entreprise' => $request->nom_entreprise,
                'email_admin_principal' => $request->email_admin,
                'statut_abonnement' => 'ESSENTIAL', // Défaut à l'inscription
                'db_name' => $dbName,
                'db_host' => $dbHost,
                'db_user' => $dbUser,
                'db_password' => $dbPassword,
            ]);

            // 4. Création de la base de données du tenant et exécution des migrations
            if (!$this->tenantService->createTenantDatabase($entreprise)) {
                throw new \Exception("Échec de la création de la base de données du tenant.");
            }
            if (!$this->tenantService->runTenantMigrations($entreprise)) {
                throw new \Exception("Échec de l'exécution des migrations du tenant.");
            }

            // 5. Création de l'UtilisateurGlobal (admin principal de l'entreprise)
            $user = UtilisateurGlobal::create([
                'id_utilisateur_global' => Str::uuid()->toString(), // Génère un UUID pour la clé primaire
                'email' => $request->email_admin,
                'password' => Hash::make($request->password),
                'id_entreprise' => $entreprise->id_entreprise,
                'est_admin_super_pilotpro' => false, // Ce n'est pas un super admin de PilotPro
            ]);

            // Commit de la transaction si tout s'est bien passé
            DB::connection('tenant_main')->commit();

            // 6. Génération du token d'accès Passport pour l'utilisateur fraîchement inscrit
            $token = $user->createToken('authToken')->accessToken;

            Log::info("Nouvelle entreprise '{$entreprise->nom_entreprise}' et utilisateur '{$user->email}' enregistrés avec succès. DB tenant: {$dbName}");

            return response()->json([
                'message' => 'Inscription réussie.',
                'user' => $user,
                'entreprise' => $entreprise,
                'access_token' => $token,
            ], Response::HTTP_CREATED);
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
