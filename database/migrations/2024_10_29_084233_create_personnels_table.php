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
        Schema::create('personnels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifiant', 10)->unique();
            $table->string('avatar', 10);
            $table->string('nom', 100)->default('nom_exemple');
            $table->string('prenom', 100)->default('prenom_exemple');
            $table->string('date_naissance', 100)->default('0000-00-00');
            $table->string('sexe', 100)->default('sexe_exemple');
            $table->integer('nb_retard')->default(0);
            $table->integer('nb_absent')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
