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
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); 
            $table->string('ref');
            $table->string('name'); 
            $table->text('desc')->nullable();
            $table->string('photo')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 10, 2); 
            $table->json('couleur')->nullable();
            $table->decimal('remise', 5, 2)->default(0); 
            $table->string('offre'); 
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
