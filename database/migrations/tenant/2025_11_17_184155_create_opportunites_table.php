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
        Schema::connection('tenant')->create('opportunites', function (Blueprint $table) {
            $table->uuid('id_opportunite')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_opportunite');
            $table->text('description')->nullable();
            $table->decimal('montant_estime', 15, 2)->nullable();
            $table->date('date_cloture_estimee')->nullable();
            $table->string('statut_etape_pipeline')->default('QUALIFICATION'); // QUALIFICATION, PROPOSITION, NEGOCIATION, GAGNEE, PERDUE
            $table->uuid('id_lead')->nullable(); // FK vers leads
            $table->uuid('id_client')->nullable(); // FK vers clients
            $table->uuid('id_utilisateur_responsable')->nullable(); // FK vers utilisateurs
            $table->timestamps();

            $table->foreign('id_lead')
                  ->references('id_lead')
                  ->on('leads')
                  ->onDelete('set null');
            $table->foreign('id_client')
                  ->references('id_client')
                  ->on('clients')
                  ->onDelete('set null');
            $table->foreign('id_utilisateur_responsable')
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
        Schema::connection('tenant')->dropIfExists('opportunites');
    }
};
