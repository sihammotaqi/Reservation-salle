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
        // 1. Add quantite back to equipements to track total inventory
        Schema::table('equipements', function (Blueprint $table) {
            $table->integer('quantite')->default(1)->after('description');
        });

        // 2. Create the pivot table for the Many-to-Many relationship
        Schema::create('equipement_salle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
            $table->foreignId('equipement_id')->constrained('equipements')->onDelete('cascade');
            $table->integer('quantite')->default(1)->comment('Quantite de cet equipement dans cette salle');
            $table->timestamps();
            
            // Un equipement ne peut etre ajoute qu'une seule fois a une salle (la quantite gere le nombre)
            $table->unique(['salle_id', 'equipement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipement_salle');
        
        Schema::table('equipements', function (Blueprint $table) {
            $table->dropColumn('quantite');
        });
    }
};
