<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends TenantModel
{
    //
    protected $table = 'entrepots';
    protected $primaryKey = 'id_entrepot';

    // RELATION : Un entrepôt contient plusieurs stocks de produits (Many-to-Many via pivot 'stocks')
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'stocks', 'id_entrepot', 'id_produit')
                    ->withPivot('quantite', 'date_derniere_maj');
    }

    // RELATION : Un entrepôt a plusieurs mouvements de stock
    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class, 'id_entrepot', 'id_entrepot');
    }
}
