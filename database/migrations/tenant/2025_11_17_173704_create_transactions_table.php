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
        Schema::connection('tenant')->create('transactions', function (Blueprint $table) {
            $table->uuid('id_transaction')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('id_compte');
            $table->text('description')->nullable();
            $table->decimal('montant', 15, 2);
            $table->string('type')->default('DEPENSE'); // DEPENSE, REVENU, TRANSFERT
            $table->timestamp('date_transaction');
            $table->timestamps();

            $table->foreign('id_compte')
                  ->references('id_compte')
                  ->on('comptes_financiers')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('transactions');
    }
};
