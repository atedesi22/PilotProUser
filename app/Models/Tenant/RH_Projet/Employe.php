<?php

namespace App\Models\Tenant\RH_Projet;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    //
    protected $table = 'employes';
    protected $primaryKey = 'id_employe';

    // RELATION : L'employé peut être lié à un utilisateur du système
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
    
    // RELATION : L'employé peut être assigné à plusieurs tâches
    public function tachesAssignees()
    {
        return $this->hasMany(Tache::class, 'assigne_a', 'id_employe');
    }
}
