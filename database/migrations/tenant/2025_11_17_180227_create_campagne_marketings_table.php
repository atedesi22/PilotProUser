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
        Schema::connection('tenant')->create('campagnes_marketing', function (Blueprint $table) {
            $table->uuid('id_campagne')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_campagne');
            $table->text('objectif')->nullable();
            $table->date('date_debut');
            $table->date('date_fin_prevue')->nullable();
            $table->string('statut_campagne')->default('BROUILLON'); // BROUILLON, ACTIVE, TERMINEE
            $table->uuid('id_projet')->nullable(); // FK vers projets
            $table->timestamps();

            $table->foreign('id_projet')
                  ->references('id_projet')
                  ->on('projets')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('campagnes_marketing');
    }
};
