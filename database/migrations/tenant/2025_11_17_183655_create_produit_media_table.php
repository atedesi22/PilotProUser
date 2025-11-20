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
        Schema::connection('tenant')->create('produit_medias', function (Blueprint $table) {
            $table->bigIncrements('id'); // Clé primaire auto-incrémentée pour la table pivot
            $table->uuid('id_produit');
            $table->uuid('id_media');
            $table->integer('ordre')->default(0); // Ordre d'affichage
            $table->boolean('est_principale')->default(false);
            $table->timestamps();

            $table->unique(['id_produit', 'id_media']); // Un média ne peut être lié qu'une fois à un produit

            $table->foreign('id_produit')
                  ->references('id_produit')
                  ->on('produits')
                  ->onDelete('cascade');
            $table->foreign('id_media')
                  ->references('id_media')
                  ->on('medias')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('produit_medias');
    }
};
