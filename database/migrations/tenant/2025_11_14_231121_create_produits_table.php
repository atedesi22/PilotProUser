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
        Schema::connection('tenant')->create('produits', function (Blueprint $table) {
            $table->uuid('id_produit')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_produit');
            $table->text('description')->nullable();
            $table->string('sku', 100)->unique()->nullable();
            $table->decimal('prix_vente', 10, 2);
            $table->decimal('prix_achat', 10, 2)->nullable();
            $table->decimal('poids', 10, 2)->nullable();
            $table->string('unite_mesure', 50)->nullable();
            $table->boolean('est_vendable_online')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('produits');
    }
};
