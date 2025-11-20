<?php

namespace App\Models\Tenant\CRM;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $table = 'clients';
    protected $primaryKey = 'id_client';
    protected $fillable = ['id_client', 'nom_complet', 'email', 'adresse_facturation'];
    protected $casts = ['adresse_facturation' => 'array'];

    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class, 'id_client', 'id_client');
    }
    
    public function opportunites(): HasMany
    {
        return $this->hasMany(Opportunite::class, 'id_client', 'id_client');
    }
}
