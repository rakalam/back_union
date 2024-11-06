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
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personnel')->constrained("personnels");
            $table->string('lundi_deb');
            $table->string('lundi_fin');
            $table->string('mardi_deb');
            $table->string('mardi_fin');
            $table->string('mercredi_deb');
            $table->string('mercredi_fin');
            $table->string('jeudi_deb');
            $table->string('jeudi_fin');
            $table->string('vendredi_deb');
            $table->string('vendedi_fin');
            $table->string('samedi_deb');
            $table->string('samedi_fin');
            $table->string('dimanche_deb');
            $table->string('dimanche_fin');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints(); 

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
