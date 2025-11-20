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
        Schema::connection('tenant_main')->create('utilisateurs_globaux', function (Blueprint $table) {
            $table->uuid('id_utilisateur_global')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('email')->unique();
            $table->string('password'); // Le champ Laravel par défaut est 'password'
            $table->uuid('id_entreprise'); // FK vers la table entreprises
            $table->boolean('est_admin_super_pilotpro')->default(false); // Pour les admins de PilotPro eux-mêmes
            
            // Photo de profil (ID du média stocké dans la DB du tenant de l'entreprise)
            $table->uuid('id_media_photo_profil')->nullable(); // FK logique

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_entreprise')
                  ->references('id_entreprise')
                  ->on('entreprises')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant_main')->dropIfExists('utilisateurs_globaux');
    }
};
