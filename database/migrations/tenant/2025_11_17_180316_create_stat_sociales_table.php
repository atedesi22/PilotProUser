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
        Schema::connection('tenant')->create('stats_sociales', function (Blueprint $table) {
            $table->uuid('id_stat')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('id_publication');
            $table->date('date_collecte');
            $table->integer('impressions')->default(0);
            $table->integer('reach')->default(0);
            $table->integer('engagement_total')->default(0);
            $table->integer('clics_lien')->default(0);
            $table->jsonb('donnees_brutes')->nullable(); // Données brutes de l'API sociale
            $table->timestamps();

            $table->foreign('id_publication')
                  ->references('id_publication')
                  ->on('publications_sociales')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('stats_sociales');
    }
};
