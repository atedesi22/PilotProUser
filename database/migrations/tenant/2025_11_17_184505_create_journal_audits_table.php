<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('tenant')->create('journal_audit', function (Blueprint $table) {
            $table->bigIncrements('id_log'); // Clé primaire auto-incrémentée
            $table->uuid('id_utilisateur')->nullable(); // FK vers utilisateurs
            $table->string('action_type'); // CREATION, MODIFICATION, SUPPRESSION, CONNEXION
            $table->string('table_cible')->nullable(); // Nom de la table affectée
            $table->uuid('id_entite_cible')->nullable(); // ID de l'enregistrement affecté
            $table->jsonb('ancienne_valeur_json')->nullable(); // État avant (pour MODIFICATION)
            $table->jsonb('nouvelle_valeur_json')->nullable(); // État après (pour CREATION, MODIFICATION)
            $table->timestamp('timestamp')->useCurrent();
            $table->string('adresse_ip')->nullable(); // Pour des raisons de sécurité
            $table->string('user_agent')->nullable(); // Navigateur/appareil

            $table->index(['id_utilisateur', 'timestamp']); // Index pour les requêtes fréquentes
            $table->index(['table_cible', 'id_entite_cible']); // Index pour le suivi d'entités spécifiques

            $table->foreign('id_utilisateur')
                  ->references('id_utilisateur')
                  ->on('utilisateurs')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('journal_audit');
    }
};
