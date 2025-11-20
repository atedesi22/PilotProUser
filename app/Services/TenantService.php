<?php

namespace App\Services;

use App\Models\Entreprise;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TenantService
{
    /**
     * Crée une nouvelle base de données PostgreSQL pour l'entreprise spécifiée.
     * Les identifiants de connexion sont pris du modèle Entreprise.
     *
     * @param Entreprise $entreprise
     * @return bool Vrai si la base de données a été créée avec succès, faux sinon.
     */
    public function createTenantDatabase(Entreprise $entreprise): bool
    {
        // Utilise la connexion principale (tenant_main) pour créer de nouvelles bases de données
        $mainDbConnection = DB::connection('tenant_main');

        try {
            // Vérifie si la base de données existe déjà pour éviter les erreurs
            $databaseExists = $mainDbConnection->select(
                "SELECT 1 FROM pg_database WHERE datname = ?", 
                [$entreprise->db_name]
            );

            if (empty($databaseExists)) {
                // IMPORTANT: Les noms de base de données ne peuvent pas être passés comme paramètres bind
                // dans certaines requêtes, donc nous devons le construire directement.
                // Assurez-vous que $entreprise->db_name est SANITIZED pour éviter les injections SQL.
                // Dans un environnement réel, générez un nom de DB unique et sûr.
                $mainDbConnection->statement("CREATE DATABASE {$entreprise->db_name}");
                Log::info("Base de données '{$entreprise->db_name}' créée pour l'entreprise '{$entreprise->nom_entreprise}'.");
            } else {
                Log::warning("La base de données '{$entreprise->db_name}' existe déjà. Skipping creation.");
            }

            // Crée l'utilisateur si nécessaire et lui donne les droits sur la DB
            // Le même avertissement sur la sanitization s'applique ici.
            // Pour l'instant, nous supposons que l'utilisateur est pré-créé ou que le 'db_user' a les droits
            // suffisants sur 'pilotpro_main' pour gérer la DB de l'entreprise.
            // En production, vous voudriez créer un rôle/utilisateur spécifique avec des permissions limitées.

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la création de la base de données pour l'entreprise '{$entreprise->nom_entreprise}': " . $e->getMessage());
            // TODO: Gérer la suppression de l'entreprise globale si la création de DB échoue.
            return false;
        }
    }

    /**
     * Exécute les migrations spécifiques au tenant sur sa nouvelle base de données.
     *
     * @param Entreprise $entreprise
     * @return bool Vrai si les migrations ont été exécutées avec succès, faux sinon.
     */
    public function runTenantMigrations(Entreprise $entreprise): bool
    {
        // Configure la connexion du tenant pour cette opération
        $this->setTenantDbConnection($entreprise);

        try {
            // Exécute les migrations spécifiques au dossier 'database/migrations/tenant'
            // sur la connexion 'tenant' nouvellement configurée.
            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--database' => 'tenant', // Utilise la connexion 'tenant' configurée dynamiquement
                '--force' => true, // Nécessaire pour exécuter les migrations en production
            ]);

            Log::info("Migrations exécutées pour la base de données '{$entreprise->db_name}'.");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'exécution des migrations pour '{$entreprise->db_name}': " . $e->getMessage());
            // TODO: Gérer le rollback ou la suppression de la DB du tenant si les migrations échouent.
            return false;
        } finally {
            // Après l'opération, re-purger la connexion 'tenant' pour s'assurer qu'elle n'est pas persistante
            // en dehors de la portée prévue, ou reconfigurer vers un état par défaut si nécessaire.
            DB::purge('tenant');
        }
    }

    /**
     * Configure dynamiquement la connexion 'tenant' de Laravel pour la base de données
     * spécifique de l'entreprise actuellement active.
     *
     * @param Entreprise $entreprise L'instance du modèle Entreprise pour le tenant actuel.
     * @return void
     */
    public function setTenantDbConnection(Entreprise $entreprise): void
    {
        // Définition de la configuration de la connexion 'tenant'
        Config::set('database.connections.tenant', [
            'driver' => 'pgsql',
            'host' => $entreprise->db_host,
            'port' => $entreprise->db_port,
            'database' => $entreprise->db_name,
            'username' => $entreprise->db_user,
            'password' => $entreprise->db_password,
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer', // Ou 'require' selon votre configuration SSL
        ]);

        // Reconnecte la connexion pour s'assurer que les modèles de tenant l'utilisent
        // et purge l'ancienne pour éviter la réutilisation de connexions périmées.
        DB::purge('tenant');
        DB::reconnect('tenant');

        Log::debug("Connexion 'tenant' configurée pour la DB: {$entreprise->db_name}");
    }

    /**
     * Supprime la base de données PostgreSQL d'un tenant.
     * ATTENTION: Opération irréversible.
     *
     * @param Entreprise $entreprise
     * @return bool
     */
    public function dropTenantDatabase(Entreprise $entreprise): bool
    {
        $mainDbConnection = DB::connection('tenant_main');
        try {
            // Ferme toutes les connexions actives à la base de données cible avant de la supprimer
            $mainDbConnection->statement("SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE pg_stat_activity.datname = '{$entreprise->db_name}' AND pid <> pg_backend_pid();");
            $mainDbConnection->statement("DROP DATABASE {$entreprise->db_name}");
            Log::info("Base de données '{$entreprise->db_name}' supprimée pour l'entreprise '{$entreprise->nom_entreprise}'.");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression de la base de données '{$entreprise->db_name}': " . $e->getMessage());
            return false;
        }
    }
}