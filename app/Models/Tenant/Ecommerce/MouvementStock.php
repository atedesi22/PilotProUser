<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends TenantModel
{
    //
    protected $table = 'mouvements_stock';
    protected $primaryKey = 'id_mouvement';
    
    // RELATION : Le mouvement appartient à un produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }

    // RELATION : Le mouvement concerne un entrepôt
    public function entrepot()
    {
        return $this->belongsTo(Entrepot::class, 'id_entrepot', 'id_entrepot');
    }
}
