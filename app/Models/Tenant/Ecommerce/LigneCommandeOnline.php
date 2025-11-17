<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class LigneCommandeOnline extends TenantModel
{
    //
    protected $table = 'lignes_commande_online';
    protected $primaryKey = 'id_ligne_commande';

    // RELATION : La ligne de commande appartient à une commande
    public function commande()
    {
        return $this->belongsTo(CommandeOnline::class, 'id_commande', 'id_commande');
    }

    // RELATION : La ligne de commande concerne un produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }
}
