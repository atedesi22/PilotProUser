<?php

namespace App\Models\Tenant\RH_Projet;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    //
    protected $table = 'projets';
    protected $primaryKey = 'id_projet';

    // RELATION : Un projet a plusieurs tâches
    public function taches()
    {
        return $this->hasMany(Tache::class, 'id_projet', 'id_projet');
    }

    public function campagnes(): HasMany // Lien vers le nouveau module PilotCom
    {
        return $this->hasMany(CampagneMarketing::class, 'id_projet', 'id_projet');
    }
}
