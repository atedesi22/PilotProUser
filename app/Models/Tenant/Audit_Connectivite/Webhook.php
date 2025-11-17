<?php

namespace App\Models\Tenant\Audit_Connectivite;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    //
    protected $table = 'webhooks';
    protected $primaryKey = 'id_webhook';
    protected $fillable = ['id_webhook', 'url_cible', 'evenement_declencheur', 'secret', 'est_actif', 'id_utilisateur_creation'];
    protected $casts = ['est_actif' => 'boolean'];
    
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur_creation', 'id_utilisateur');
    }
}
