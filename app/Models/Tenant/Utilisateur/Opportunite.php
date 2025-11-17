<?php

namespace App\Models\Tenant\Utilisateur;

use Illuminate\Database\Eloquent\Model;

class Opportunite extends Model
{
    //
    protected $table = 'opportunites';
    protected $primaryKey = 'id_opportunite';
    protected $fillable = ['id_opportunite', 'nom_opportunite', 'montant_estime', 'date_cloture_estimee', 'statut_etape_pipeline', 'id_lead', 'id_client', 'id_utilisateur_responsable'];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'id_lead', 'id_lead');
    }
    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur_responsable', 'id_utilisateur');
    }
}
