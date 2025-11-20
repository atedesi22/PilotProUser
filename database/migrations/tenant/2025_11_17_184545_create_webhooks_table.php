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
        Schema::connection('tenant')->create('webhooks', function (Blueprint $table) {
            $table->uuid('id_webhook')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('url_cible');
            $table->string('evenement_declencheur'); // COMMANDE_CREEE, DEVIS_ACCEPTE, STOCK_MODIFIE
            $table->string('secret')->nullable(); // Pour la signature HMAC
            $table->boolean('est_actif')->default(true);
            $table->uuid('id_utilisateur_creation')->nullable(); // Qui a configuré le webhook
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
        Schema::connection('tenant')->dropIfExists('webhooks');
    }
};
