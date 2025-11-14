<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TenantModel extends Model
{
    //
    // Tous les modèles de tenant utiliseront la connexion 'tenant' configurée dynamiquement
    protected $connection = 'tenant'; 
    public $incrementing = false;
    protected $keyType = 'string';
}
