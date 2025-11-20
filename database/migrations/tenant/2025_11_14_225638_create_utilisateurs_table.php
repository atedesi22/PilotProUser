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
        Schema::connection('tenant')->create('utilisateurs', function (Blueprint $table) {
            $table->uuid('id_utilisateur')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_complet');
            $table->string('email')->unique();
            $table->string('mot_de_passe'); // Le champ Laravel par défaut est 'password'
            $table->uuid('id_role'); // FK vers la table roles
            $table->uuid('id_media_photo_profil')->nullable(); // FK vers la table medias

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_role')
                  ->references('id_role')
                  ->on('roles')
                  ->onDelete('restrict'); // Un rôle ne doit pas être supprimé s'il est utilisé
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('utilisateurs');
    }
};
