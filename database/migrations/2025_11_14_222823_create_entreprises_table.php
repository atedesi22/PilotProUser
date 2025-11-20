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
        Schema::connection('tenant_main')->create('entreprises', function (Blueprint $table) {
            $table->uuid('id_entreprise')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_entreprise');
            $table->string('email_admin_principal')->unique();
            $table->timestamp('date_inscription')->useCurrent();
            $table->string('statut_abonnement')->default('ESSENTIAL'); // ESSENTIAL, PRO, ENTREPRISE
            $table->timestamp('date_fin_abonnement')->nullable();
            
            // Informations de connexion à la base de données du tenant
            $table->string('db_name');
            $table->string('db_host')->nullable(); // Peut être null si on utilise le même hôte
            $table->string('db_port')->default('5432');
            $table->string('db_user');
            $table->string('db_password');
            
            // Pour l'intégration Stripe (monétisation)
            $table->string('id_stripe_customer')->nullable(); 

            // Logo de l'entreprise (ID du média stocké dans sa DB tenant)
            $table->uuid('id_media_logo')->nullable(); // FK logique, pas physique

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant_main')->dropIfExists('entreprises');
    }
};
