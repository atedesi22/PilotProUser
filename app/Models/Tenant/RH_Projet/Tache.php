<?php

namespace App\Models\Tenant\RH_Projet;

use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    //
    protected $table = 'taches';
    protected $primaryKey = 'id_tache';

    // RELATION : La tâche appartient à un projet
    public function projet()
    {
        return $this->belongsTo(Projet::class, 'id_projet', 'id_projet');
    }

    // RELATION : La tâche est assignée à un employé
    public function assigneA()
    {
        return $this->belongsTo(Employe::class, 'assigne_a', 'id_employe');
    }
}
