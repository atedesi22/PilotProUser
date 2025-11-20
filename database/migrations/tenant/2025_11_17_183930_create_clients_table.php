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
        Schema::connection('tenant')->create('clients', function (Blueprint $table) {
            $table->uuid('id_client')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_complet');
            $table->string('email')->unique()->nullable();
            $table->string('telephone')->nullable();
            $table->jsonb('adresse_facturation')->nullable(); // Adresse complète en JSONB
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('clients');
    }
};
