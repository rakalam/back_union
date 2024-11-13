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
       // Modifier la contrainte sur la table 'absents'
       Schema::table('absents', function (Blueprint $table) {
        // Supprimer la contrainte de clé étrangère existante
        $table->dropForeign(['id_personnel']);

        // Ajouter la nouvelle contrainte avec onDelete('cascade')
        $table->foreign('id_personnel')
              ->references('id')
              ->on('personnels')
              ->onDelete('cascade');
    });

    // Modifier la contrainte sur la table 'retards'
    Schema::table('retards', function (Blueprint $table) {
        // Supprimer la contrainte de clé étrangère existante
        $table->dropForeign(['id_personnel']);

        // Ajouter la nouvelle contrainte avec onDelete('cascade')
        $table->foreign('id_personnel')
              ->references('id')
              ->on('personnels')
              ->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           // Revenir à l'état précédent (sans la contrainte onDelete)
        Schema::table('absents', function (Blueprint $table) {
            $table->dropForeign(['id_personnel']);
            $table->foreign('id_personnel')
                  ->references('id')
                  ->on('personnels');
        });

        Schema::table('retards', function (Blueprint $table) {
            $table->dropForeign(['id_personnel']);
            $table->foreign('id_personnel')
                  ->references('id')
                  ->on('personnels');
        });
    }
};
