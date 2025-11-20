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
        Schema::connection('tenant')->create('taches', function (Blueprint $table) {
            $table->uuid('id_tache')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('id_projet');
            $table->string('nom_tache');
            $table->text('description')->nullable();
            $table->date('date_echeance')->nullable();
            $table->string('statut_tache')->default('A_FAIRE'); // A_FAIRE, EN_COURS, TERMINE, BLOQUE
            $table->uuid('assigne_a')->nullable(); // FK vers employes
            $table->timestamps();

            $table->foreign('id_projet')
                  ->references('id_projet')
                  ->on('projets')
                  ->onDelete('cascade');
            $table->foreign('assigne_a')
                  ->references('id_employe')
                  ->on('employes')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('taches');
    }
};
