<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class CommandeOnline extends TenantModel
{
    //
    protected $table = 'commandes_online';
    protected $primaryKey = 'id_commande';

    // RELATION : Une commande a plusieurs lignes de commande
    public function lignes()
    {
        return $this->hasMany(LigneCommandeOnline::class, 'id_commande', 'id_commande');
    }
}
