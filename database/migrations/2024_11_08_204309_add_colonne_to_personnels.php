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
        Schema::table('personnels', function (Blueprint $table) {

            $table->string('photos')->nullable();
            $table->string('adresse', 100)->default('exemple_adresse');
            $table->string('contact', 50)->default('exemple_contact');
            $table->string('cin', 50)->default('exemple_cin');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            $table->dropColumn('photos');
            $table->dropColumn('adresse');
            $table->dropColumn('contact');
            $table->dropColumn('cin');
        });
    }
};
