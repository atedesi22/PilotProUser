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
        'id_entreprise', 'est_admin_super_pilotpro'
    ];
    
    // Pour l'authentification
    protected $hidden = ['password_hash']; 

    // RELATION : Un utilisateur global appartient à une entreprise
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise', 'id_entreprise');
    }
}
