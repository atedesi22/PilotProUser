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
        Schema::connection('tenant')->create('entrepots', function (Blueprint $table) {
            $table->uuid('id_entrepot')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('nom_entrepot');
            $table->text('adresse')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('entrepots');
    }
};
