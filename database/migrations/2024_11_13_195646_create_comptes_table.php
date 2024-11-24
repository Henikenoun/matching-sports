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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prénom');
            $table->date('date de naissance');
            $table->string('ville');
            $table->string('numéro de téléphone');
            $table->string('email');
            $table->string('mot de passe');
            $table->boolean('confirmed')->nullable()->default(false);('transport');
            $table->string('photo');
           // $table->boolean('confirmed')->nullable()->default(false);('disponibilité');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
