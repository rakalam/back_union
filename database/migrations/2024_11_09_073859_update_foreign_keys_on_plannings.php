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
        Schema::table('plannings', function (Blueprint $table) {
            // Supprimer la clé étrangère existante
            $table->dropForeign(['id_personnel']);

            // Ajouter la clé étrangère avec l'option de suppression en cascade
            $table->foreign('id_personnel')->references('id')->on('personnels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plannings', function (Blueprint $table) {
            // Annuler la suppression en cascade en remettant la contrainte sans cascade
            $table->dropForeign(['id_personnel']);
            $table->foreign('id_personnel')->references('id')->on('personnels');
        });
    }
};
