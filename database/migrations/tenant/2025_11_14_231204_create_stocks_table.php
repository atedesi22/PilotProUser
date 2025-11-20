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
        Schema::connection('tenant')->create('stocks', function (Blueprint $table) {
            $table->uuid('id_produit');
            $table->uuid('id_entrepot');
            $table->integer('quantite');
            $table->timestamp('date_derniere_maj')->useCurrent();
            $table->timestamps();

            $table->primary(['id_produit', 'id_entrepot']); // Clé primaire composite

            $table->foreign('id_produit')
                  ->references('id_produit')
                  ->on('produits')
                  ->onDelete('cascade');
            $table->foreign('id_entrepot')
                  ->references('id_entrepot')
                  ->on('entrepots')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('stocks');
    }
};
