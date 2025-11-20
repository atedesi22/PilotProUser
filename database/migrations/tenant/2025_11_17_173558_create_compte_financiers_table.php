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
        Schema::connection('tenant')->create('comptes_financiers', function (Blueprint $table) {
            $table->uuid('id_compte')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_compte');
            $table->string('type_compte')->default('CASH'); // CASH, BANQUE, CARTE_CREDIT
            $table->decimal('solde', 15, 2)->default(0.00);
            $table->string('devise')->default('EUR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('comptes_financiers');
    }
};
