<?php

namespace App\Models\Tenant\Utilisateur;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    //
    protected $table = 'leads';
    protected $primaryKey = 'id_lead';
    protected $fillable = ['id_lead', 'nom_complet', 'email', 'telephone', 'statut_lead', 'id_utilisateur_responsable'];

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur_responsable', 'id_utilisateur');
    }
    
    public function opportunites(): HasMany
    {
        return $this->hasMany(Opportunite::class, 'id_lead', 'id_lead');
    }
}
