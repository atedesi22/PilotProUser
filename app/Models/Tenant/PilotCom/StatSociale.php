<?php

namespace App\Models\Tenant\PilotCom;

use Illuminate\Database\Eloquent\Model;

class StatSociale extends Model
{
    //
    protected $table = 'stats_sociales';
    protected $primaryKey = 'id_stat';
    protected $fillable = [
        'id_stat', 'id_publication', 'date_collecte', 'impressions', 
        'reach', 'engagement_total', 'clics_lien', 'donnees_brutes'
    ];
    protected $casts = ['donnees_brutes' => 'array'];
    
    public function publication(): BelongsTo
    {
        return $this->belongsTo(PublicationSociale::class, 'id_publication', 'id_publication');
    }
}
