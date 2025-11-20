<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens; // Si vous utilisez Laravel Passport


class UtilisateurGlobal extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    //
    protected $connection = 'tenant_main';
    protected $table = 'utilisateurs_globaux';
    protected $primaryKey = 'id_utilisateur_global';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_utilisateur_global', 'email', 'password_hash', 
        'id_entreprise', 'est_admin_super_pilotpro', 'id_media_photo_profil'
    ];
    
    // Pour l'authentification
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // Si vous avez une vérification d'email
        'password' => 'hashed', // Laravel 10+ préfère le cast 'hashed'
        'est_admin_super_pilotpro' => 'boolean',
    ];

    // RELATION : Un utilisateur global appartient à une entreprise
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise', 'id_entreprise');
    }

    public function photoProfil() // Relation logique vers la table medias du tenant
    {
        // Retournera une instance de Media si le tenant est résolu, sinon null.
        // La logique pour récupérer le média sera implémentée dans un accesseur ou un service.
        if ($this->id_media_photo_profil && app()->bound('currentTenant')) {
            return \App\Models\Tenant\Media::on('tenant')->find($this->id_media_photo_profil);
        }
        return null;
    }
}
