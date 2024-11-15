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
            $table->string("Nom_Club");
            $table->integer("Nb_Place");
            $table->boolean("Complet");
            $table->boolean("ispaye");
            $table->string("Type");
            $table->date("Date_Reservation");
            $table->date("Date_TempsReel");
            $table->json("Participants");
            $table->timestamps();
            $table->foreign("ID")
            ->references('id')
            ->on('Terain')
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
