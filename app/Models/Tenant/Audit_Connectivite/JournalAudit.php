<?php

namespace App\Models\Tenant\Audit_Connectivite;

use Illuminate\Database\Eloquent\Model;

class JournalAudit extends Model
{
    //
    protected $table = 'journal_audit';
    protected $primaryKey = 'id_log';
    public $incrementing = true; // BIGINT auto-incrémenté
    protected $keyType = 'int';
    protected $fillable = [
        'id_utilisateur', 'action_type', 'table_cible', 'id_entite_cible', 
        'ancienne_valeur_json', 'nouvelle_valeur_json'
    ];
    protected $casts = [
        'ancienne_valeur_json' => 'array',
        'nouvelle_valeur_json' => 'array',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
}
