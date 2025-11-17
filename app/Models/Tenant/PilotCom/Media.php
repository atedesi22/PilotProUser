<?php

namespace App\Models\Tenant\PilotCom;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $table = 'medias';
    protected $primaryKey = 'id_media';
    protected $fillable = [
        'id_media', 'nom_fichier', 'type_media', 'chemin_stockage', 
        'taille_ko', 'tags', 'id_utilisateur_upload'
    ];
    protected $casts = ['tags' => 'array'];

    public function uploadePar(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur_upload', 'id_utilisateur');
    }
    
    public function publications(): HasMany
    {
        return $this->hasMany(PublicationSociale::class, 'id_media_principal', 'id_media');
    }

    public function produits(): HasMany // Pour savoir quels produits utilisent ce média
    {
        return $this->hasMany(Produit::class, 'id_media_principal', 'id_media');
    }
}
