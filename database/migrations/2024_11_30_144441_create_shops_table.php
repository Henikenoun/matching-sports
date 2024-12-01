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
        Schema::create('shops', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->text('desc')->nullable();
            $table->json('photos')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('club_id')->nullable(); 
            $table->timestamps(); 

            
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
