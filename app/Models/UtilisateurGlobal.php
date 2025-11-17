<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UtilisateurGlobal extends Authenticatable
{
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
    protected $hidden = ['password_hash']; 

    // RELATION : Un utilisateur global appartient à une entreprise
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise', 'id_entreprise');
    }

    public function photoProfil() // Relation logique vers la table medias du tenant
    {
        // Similaire au logo de l'entreprise, cette relation est logique et inter-DB.
        // La récupération se fera via un service après résolution du tenant.
        return null;
    }
}
