<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvenementParticipantTable extends Migration
{
    public function up(): void
    {
        Schema::create('evenement_participant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evenement_id');
            $table->unsignedBigInteger('participant_id');
            $table->timestamps();

            $table->foreign('evenement_id')->references('id')->on('evenements')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenement_participant');
    }
}