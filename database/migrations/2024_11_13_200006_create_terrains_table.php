<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerrainsTable extends Migration
{
    public function up(): void
    {
        Schema::create('terrains', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom');
            $table->string('type');
            $table->boolean('disponibilite')->default(true); // Set default value to true
            $table->integer('capacite');
            $table->integer('fraisLocation');
            $table->unsignedBigInteger('club_id'); // Foreign key
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('restrict'); // Correction ici
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terrains');
    }
}