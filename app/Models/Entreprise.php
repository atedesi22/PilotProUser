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
        'id_stripe_customer'
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
