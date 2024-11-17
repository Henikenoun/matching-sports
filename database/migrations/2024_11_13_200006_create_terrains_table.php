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
            $table->foreignId('club_id')->constrained('clubs')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terrains');
    }
}
