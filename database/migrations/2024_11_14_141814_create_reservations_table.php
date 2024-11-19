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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string("User_Reserve");
            $table->integer("Nb_Place");
            $table->boolean("Complet");
            $table->boolean("ispaye");
            $table->string("Type");
            $table->date("Date_Reservation");
            $table->date("Date_TempsReel");
            $table->json("Participants");
            $table->unsignedBigInteger('terrain_id'); // Clé étrangère
            $table->unsignedBigInteger('club_id'); // Clé étrangère

            $table->timestamps();

            // Définition de la clé étrangère
            $table->foreign("terrain_id")
                  ->references('id')
                  ->on('terrains') // Nom correct de la table
                  ->onDelete('restrict');
            $table->foreign("club_id")
                    ->references('id')
                    ->on('clubs') // Nom correct de la table
                    ->onDelete('restrict');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
