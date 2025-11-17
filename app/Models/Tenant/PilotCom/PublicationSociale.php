<?php

namespace App\Models\Tenant\PilotCom;

use Illuminate\Database\Eloquent\Model;

class PublicationSociale extends Model
{
    //
    protected $table = 'publications_sociales';
    protected $primaryKey = 'id_publication';
    protected $fillable = [
        'id_publication', 'id_campagne', 'id_media_principal', 
        'texte_contenu', 'plateforme', 'date_planification', 
        'statut_publication', 'url_post_final'
    ];
    
    public function campagne(): BelongsTo
    {
        return $this->belongsTo(CampagneMarketing::class, 'id_campagne', 'id_campagne');
    }
    
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'id_media_principal', 'id_media');
    }
    
    public function stats(): HasMany
    {
        return $this->hasMany(StatSociale::class, 'id_publication', 'id_publication');
    }
}
