<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: modifier l'enum pour ajouter 'annule'
        DB::statement("ALTER TABLE plannings MODIFY COLUMN statut ENUM('en_attente', 'approuve', 'rejete', 'annule') NOT NULL DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE plannings MODIFY COLUMN statut ENUM('en_attente', 'approuve', 'rejete') NOT NULL DEFAULT 'en_attente'");
    }
};
