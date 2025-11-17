<?php

namespace App\Models\Tenant\Automatisation;

use Illuminate\Database\Eloquent\Model;

class RegleAutomatisation extends Model
{
    //
    protected $table = 'regles_automatisation';
    protected $primaryKey = 'id_regle';
    protected $fillable = ['id_regle', 'nom_regle', 'evenement_declencheur', 'conditions_json', 'action_executee', 'parametres_action_json', 'est_active'];
    protected $casts = [
        'conditions_json' => 'array',
        'parametres_action_json' => 'array',
        'est_active' => 'boolean',
    ];
}
