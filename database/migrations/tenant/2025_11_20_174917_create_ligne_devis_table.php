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
        Schema::connection('tenant')->create('lignes_devis', function (Blueprint $table) {
            $table->uuid('id_ligne_devis')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('id_devis');
            $table->uuid('id_produit')->nullable(); // Peut être null si service non catalogué
            $table->string('description_service')->nullable(); // Description pour services non catalogués
            $table->integer('quantite');
            $table->decimal('prix_unitaire_negocie', 15, 2);
            $table->decimal('remise_pourcentage', 5, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_devis')
                  ->references('id_devis')
                  ->on('devis')
                  ->onDelete('cascade');
            $table->foreign('id_produit')
                  ->references('id_produit')
                  ->on('produits')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('lignes_devis');
    }
};
