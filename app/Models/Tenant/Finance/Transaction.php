<?php

namespace App\Models\Tenant\Finance;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = 'transactions';
    protected $primaryKey = 'id_transaction';

    // RELATION : Une transaction appartient à un compte financier
    public function compte()
    {
        return $this->belongsTo(CompteFinancier::class, 'id_compte', 'id_compte');
    }
}
