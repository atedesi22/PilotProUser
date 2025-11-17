<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    //
    // Utilise la connexion 'tenant_main' définie dans config/database.php
    protected $connection = 'tenant_main'; 
    protected $table = 'entreprises';
    protected $primaryKey = 'id_entreprise';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_entreprise', 'nom_entreprise', 'email_admin_principal', 
        'date_inscription', 'statut_abonnement', 'date_fin_abonnement',
        'db_name', 'db_host', 'db_port', 'db_user', 'db_password', 
        'id_stripe_customer', 'id_media_logo'
    ];

    // RELATION : Une entreprise a plusieurs utilisateurs globaux (administrateurs principaux)
    public function utilisateursGlobaux()
    {
        return $this->hasMany(UtilisateurGlobal::class, 'id_entreprise', 'id_entreprise');
    }

    /**
     * Méthode statique pour connecter la DB du tenant.
     * @param Entreprise $entreprise
     */

    public function logo() // Cette relation doit être chargée après la résolution du tenant
    {
        // Ceci est une relation logique car 'medias' est dans la DB du tenant.
        // Vous devrez la charger manuellement après avoir configuré la connexion du tenant.
        // Ex: if (app()->bound('currentTenant')) { return Media::on('tenant')->find($this->id_media_logo); }
        // Ou gérer une relation 'HasOne' via un Scope ou un Accesseur si l'ID est dans la BD globale.
        // Pour simplifier ici, on ne met pas de relation Eloquent directe sur un modèle cross-DB.
        // On ajoutera juste la colonne à la migration. La logique de récupération sera dans un service.
        return null; // Pas de relation Eloquent directe ici pour l'instant
    }

    
    public static function setTenantDbConnection(self $entreprise): void
    {
        // Configuration dynamique de la connexion du tenant
        \Config::set('database.connections.tenant', [
            'driver' => 'pgsql', // PostgreSQL
            'host' => $entreprise->db_host,
            'port' => $entreprise->db_port,
            'database' => $entreprise->db_name,
            'username' => $entreprise->db_user,
            'password' => $entreprise->db_password,
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
        // S'assure que la connexion par défaut pour les modèles de tenant est celle-ci.
        \DB::purge('tenant'); 
        \DB::reconnect('tenant');
    }
}
