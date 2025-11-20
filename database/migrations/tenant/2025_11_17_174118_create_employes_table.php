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
        Schema::connection('tenant')->create('employes', function (Blueprint $table) {
            $table->uuid('id_employe')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_complet');
            $table->string('email_pro')->unique();
            $table->string('poste');
            $table->decimal('salaire', 10, 2)->nullable();
            $table->date('date_embauche');
            $table->uuid('id_utilisateur')->unique()->nullable(); // Lien optionnel vers un compte utilisateur
            $table->uuid('id_media_photo_profil')->nullable(); // FK vers la table medias

            $table->timestamps();

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
        Schema::connection('tenant')->dropIfExists('employes');
    }
};
