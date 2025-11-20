<?php

namespace App\Models\Tenant\CRM;

use Illuminate\Database\Eloquent\Model;

class LigneDevis extends Model
{
    //
    protected $table = 'lignes_devis';
    protected $primaryKey = 'id_ligne_devis';
    protected $fillable = [
        'id_ligne_devis', 
        'id_devis', 
        'id_produit', 
        'quantite', 
        'prix_unitaire_negocie', // On utilise 'negocie' pour refléter une potentielle remise
        'remise_pourcentage' // Ajout possible
    ];

    /**
     * Une ligne de devis appartient à un devis.
     */
    public function devis(): BelongsTo
    {
        return $this->belongsTo(Devis::class, 'id_devis', 'id_devis');
    }

    /**
     * Une ligne de devis référence un produit du catalogue (sauf si c'est un service libre).
     */
    public function produit(): BelongsTo
    {
        // La colonne 'id_produit' peut être nullable si la ligne est un service ou produit non catalogué.
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }
}
