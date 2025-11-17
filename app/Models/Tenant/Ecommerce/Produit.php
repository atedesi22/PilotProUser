<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    //
    protected $table = 'produits';
    protected $primaryKey = 'id_produit';

    protected $fillable = [
        'id_produit', 'nom_produit', 'description', 'sku', 'prix_vente', 
        'prix_achat', 'poids', 'unite_mesure', 'est_vendable_online'
        // 'id_media_principal' est retiré si la gestion est via produit_medias
    ];

    // RELATION : Un produit peut avoir du stock dans plusieurs entrepôts (Many-to-Many via pivot 'stocks')
    public function entrepots()
    {
        return $this->belongsToMany(Entrepot::class, 'stocks', 'id_produit', 'id_entrepot')
                    ->withPivot('quantite', 'date_derniere_maj');
    }

    // RELATION : Un produit a plusieurs mouvements de stock
    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class, 'id_produit', 'id_produit');
    }
    
    // RELATION : Un produit peut être dans plusieurs lignes de commande
    public function lignesCommande()
    {
        return $this->hasMany(LigneCommandeOnline::class, 'id_produit', 'id_produit');
    }

    // public function imagePrincipale(): BelongsTo // <-- NOUVELLE RELATION MEDIA
    // {
    //     return $this->belongsTo(Media::class, 'id_media_principal', 'id_media');
    // }

    /**
     * Un produit a plusieurs médias (images, vidéos) via la table pivot produit_medias.
     */
    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'produit_medias', 'id_produit', 'id_media')
                    ->withPivot('ordre', 'est_principale')
                    ->orderBy('pivot_ordre');
    }

    /**
     * Récupère l'image principale du produit.
     */
    public function imagePrincipale(): HasOne
    {
        return $this->hasOne(ProduitMedia::class, 'id_produit', 'id_produit')
                    ->where('est_principale', true);
    }
}
