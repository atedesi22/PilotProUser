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
        Schema::connection('tenant')->create('projets', function (Blueprint $table) {
            $table->uuid('id_projet')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_projet');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin_estimee')->nullable();
            $table->string('statut')->default('EN_COURS'); // EN_COURS, TERMINE, EN_ATTENTE
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('projets');
    }
};
