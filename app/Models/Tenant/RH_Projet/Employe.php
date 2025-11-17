<?php

namespace App\Models\Tenant\RH_Projet;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    //
    protected $table = 'employes';
    protected $primaryKey = 'id_employe';
    protected $fillable = ['id_employe', 'nom_complet', 'id_media_photo_profil', 'email_pro', 'poste', 'salaire', 'date_embauche', 'id_utilisateur'];
    // RELATION : L'employé peut être lié à un utilisateur du système
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
    
    // RELATION : L'employé peut être assigné à plusieurs tâches
    public function tachesAssignees()
    {
        return $this->hasMany(Tache::class, 'assigne_a', 'id_employe');
    }

    public function photoProfil(): BelongsTo // <-- NOUVELLE RELATION
    {
        return $this->belongsTo(Media::class, 'id_media_photo_profil', 'id_media');
    }
}
