<?php

namespace App\Models\Tenant\Finance;

use Illuminate\Database\Eloquent\Model;

class CompteFinancier extends Model
{
    //
    protected $table = 'comptes_financiers';
    protected $primaryKey = 'id_compte';

    // RELATION : Un compte a plusieurs transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_compte', 'id_compte');
    }
}
