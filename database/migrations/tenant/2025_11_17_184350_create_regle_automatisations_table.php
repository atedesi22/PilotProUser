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
        Schema::connection('tenant')->create('regles_automatisation', function (Blueprint $table) {
            $table->uuid('id_regle')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_regle');
            $table->string('evenement_declencheur'); // STOCK_BAS, FACTURE_RETARD, NOUVELLE_COMMANDE
            $table->jsonb('conditions_json')->nullable(); // Ex: {"seuil_stock": 10}
            $table->string('action_executee'); // CREER_TACHE, ENVOYER_EMAIL, NOTIFIER_WEBHOOK
            $table->jsonb('parametres_action_json')->nullable(); // Ex: {"id_employe_cible": "...", "template_email": "..."}
            $table->boolean('est_active')->default(true);
            $table->uuid('id_utilisateur_creation')->nullable(); // Qui a créé la règle
            $table->timestamps();

            $table->foreign('id_utilisateur_creation')
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
        Schema::connection('tenant')->dropIfExists('regles_automatisation');
    }
};
