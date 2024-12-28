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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('terrain_id')->nullable();
        $table->unsignedBigInteger('club_id')->nullable();
        $table->string('nom')->nullable();
        $table->string('type')->nullable();
        $table->integer('nombreMax')->nullable();
        $table->date('date')->nullable();
        $table->integer('nbActuel')->nullable();
        $table->text('description')->nullable();
        $table->string('photo')->nullable();
        $table->decimal('prixUnitaire', 8, 2)->nullable();
        $table->unsignedBigInteger('responsable')->nullable();
        $table->unsignedBigInteger('participant')->nullable();  
        $table->text('raison')->nullable();
        $table->timestamps();

        $table->foreign('terrain_id')->references('id')->on('terrains');
        $table->foreign('responsable')->references('id')->on('users');
        $table->foreign('participant')->references('id')->on('users');
        $table->foreign('club_id')->references('id')->on('clubs');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
