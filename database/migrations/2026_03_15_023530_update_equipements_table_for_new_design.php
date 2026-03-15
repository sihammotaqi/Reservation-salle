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
        Schema::table('equipements', function (Blueprint $table) {
            $table->text('description')->nullable()->after('nom');
            
            // Drop the foreign key and columns that are no longer needed
            if (Schema::hasColumn('equipements', 'salle_id')) {
                $table->dropForeign(['salle_id']);
                $table->dropColumn('salle_id');
            }
            if (Schema::hasColumn('equipements', 'quantite')) {
                $table->dropColumn('quantite');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipements', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->integer('quantite')->default(1);
            $table->foreignId('salle_id')->nullable()->constrained('salles')->nullOnDelete();
        });
    }
};
