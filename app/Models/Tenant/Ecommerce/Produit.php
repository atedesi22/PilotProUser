<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    //
    protected $table = 'produits';
    protected $primaryKey = 'id_produit';

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
}
