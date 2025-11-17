<?php

namespace App\Models\Tenant\PilotCom;

use Illuminate\Database\Eloquent\Model;

class CampagneMarketing extends Model
{
    //
    protected $table = 'campagnes_marketing';
    protected $primaryKey = 'id_campagne';
    protected $fillable = ['id_campagne', 'nom_campagne', 'objectif', 'date_debut', 'date_fin_prevue', 'statut_campagne', 'id_projet'];
    
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'id_projet', 'id_projet');
    }
    
    public function publications(): HasMany
    {
        return $this->hasMany(PublicationSociale::class, 'id_campagne', 'id_campagne');
    }
}
