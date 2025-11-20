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
        Schema::connection('tenant')->create('devis', function (Blueprint $table) {
            $table->uuid('id_devis')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('id_client');
            $table->uuid('id_opportunite')->nullable(); // Lien avec une opportunité
            $table->string('statut_devis')->default('BROUILLON'); // BROUILLON, ENVOYE, ACCEPTE, REJETE, FACTURE
            $table->decimal('total_ht', 15, 2);
            $table->decimal('total_ttc', 15, 2);
            $table->date('date_expiration')->nullable();
            $table->text('notes_client')->nullable();
            $table->timestamps();

            $table->foreign('id_client')
                  ->references('id_client')
                  ->on('clients')
                  ->onDelete('restrict');
            $table->foreign('id_opportunite')
                  ->references('id_opportunite')
                  ->on('opportunites')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('devis');
    }
};
