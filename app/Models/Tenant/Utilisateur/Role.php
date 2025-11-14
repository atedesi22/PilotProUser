<?php

namespace App\Models\Tenant\Utilisateur;

use Illuminate\Database\Eloquent\Model;

class Role extends TenantModel
{
    //
    protected $table = 'roles';
    protected $primaryKey = 'id_role';
    
    protected $fillable = ['id_role', 'nom_role', 'permissions'];
    protected $casts = ['permissions' => 'array'];

    // RELATION : Un rôle a plusieurs utilisateurs
    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'id_role', 'id_role');
    }
}
