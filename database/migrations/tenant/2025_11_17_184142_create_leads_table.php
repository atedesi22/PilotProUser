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
        Schema::connection('tenant')->create('leads', function (Blueprint $table) {
            $table->uuid('id_lead')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_complet');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('statut_lead')->default('NOUVEAU'); // NOUVEAU, CONTACTE, QUALIFIE, PERDU
            $table->text('notes')->nullable();
            $table->uuid('id_utilisateur_responsable')->nullable(); // FK vers utilisateurs
            $table->timestamps();

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
        Schema::connection('tenant')->dropIfExists('leads');
    }
};
