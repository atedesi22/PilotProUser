<?php

namespace App\Models\Tenant\Utilisateur;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Utilisateur extends Authenticatable
{
    //
    protected $connection = 'tenant';
    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id_utilisateur', 'nom_complet', 'email', 'mot_de_passe', 'id_role'];
    protected $hidden = ['mot_de_passe'];

    // RELATION : Un utilisateur a un rôle
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
    
    // RELATION : Un utilisateur peut être un employé
    public function employe()
    {
        return $this->hasOne(Employe::class, 'id_utilisateur', 'id_utilisateur');
    }
}
