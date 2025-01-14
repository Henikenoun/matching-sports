<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // ID principal
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur qui fait l'évaluation
            $table->unsignedBigInteger('rateable_id'); // ID de l'entité évaluée (polymorphisme)
            $table->string('rateable_type'); // Type de l'entité évaluée (club, user, événement)
            $table->tinyInteger('rating')->unsigned(); // Score (par exemple, entre 1 et 5)
            $table->text('review')->nullable(); // Commentaire optionnel
            $table->timestamps(); // created_at et updated_at

            // Relation avec la table users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
