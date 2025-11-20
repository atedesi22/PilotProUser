<?php

namespace App\Models\Tenant\CRM;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    //
    protected $table = 'devis';
    protected $primaryKey = 'id_devis';
    protected $fillable = [
        'id_devis', 
        'id_client', 
        'statut_devis', 
        'total_ht', 
        'total_ttc', // Ajout d'une colonne pour le total TTC
        'date_expiration', 
        'notes_client' // Ajout possible pour des commentaires
    ];

    /**
     * Un devis appartient à un client.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    /**
     * Un devis contient plusieurs lignes de devis.
     */
    public function lignes(): HasMany
    {
        return $this->hasMany(LigneDevis::class, 'id_devis', 'id_devis');
    }
}
