<?php

namespace App\Models\Tenant\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class ProduitMedia extends Model
{
    //
    protected $table = 'produit_medias';
    protected $primaryKey = 'id'; // Clé primaire auto-incrémentée
    public $incrementing = true; // La clé primaire est auto-incrémentée
    protected $keyType = 'int';

    protected $fillable = ['id_produit', 'id_media', 'ordre', 'est_principale'];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'id_media', 'id_media');
    }
}
