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
        Schema::connection('tenant')->create('publications_sociales', function (Blueprint $table) {
            $table->uuid('id_publication')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('id_campagne');
            $table->uuid('id_media_principal')->nullable(); // FK vers medias
            $table->text('texte_contenu');
            $table->string('plateforme'); // FACEBOOK, INSTAGRAM, LINKEDIN, X
            $table->timestamp('date_planification');
            $table->string('statut_publication')->default('PLANIFIEE'); // PLANIFIEE, PUBLIEE, ECHOUÉE, BROUILLON
            $table->string('url_post_final')->nullable(); // Lien direct vers le post
            $table->timestamps();

            $table->foreign('id_campagne')
                  ->references('id_campagne')
                  ->on('campagnes_marketing')
                  ->onDelete('cascade');
            $table->foreign('id_media_principal')
                  ->references('id_media')
                  ->on('medias')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('publications_sociales');
    }
};
