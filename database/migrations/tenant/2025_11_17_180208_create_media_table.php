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
        Schema::connection('tenant')->create('medias', function (Blueprint $table) {
            $table->uuid('id_media')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_fichier');
            $table->string('type_media')->default('image'); // image, video, pdf, creation_canvas
            $table->string('chemin_stockage'); // URL ou chemin S3/Cloud
            $table->integer('taille_ko')->nullable();
            $table->jsonb('tags')->nullable(); // Pour la recherche
            $table->uuid('id_utilisateur_upload')->nullable(); // FK vers utilisateurs
            $table->timestamps();

            $table->foreign('id_utilisateur_upload')
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
        Schema::connection('tenant')->dropIfExists('medias');
    }
};
