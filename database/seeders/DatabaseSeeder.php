<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Salle;
use App\Models\Planning;
use App\Models\Equipement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@sallepro.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $responsable = User::firstOrCreate(
            ['email' => 'responsable@sallepro.com'],
            [
                'name' => 'Responsable A',
                'password' => Hash::make('password'),
                'role' => 'responsible',
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@sallepro.com'],
            [
                'name' => 'Utilisateur Standard',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // 2. Equipements
        $projecteur = Equipement::create(['nom' => 'Projecteur 4K', 'description' => 'Vidéoprojecteur haute définition']);
        $tableau = Equipement::create(['nom' => 'Tableau interactif', 'description' => 'Tableau blanc interactif avec stylet']);
        $micro = Equipement::create(['nom' => 'Microphone sans fil', 'description' => 'Système audio pour conférences']);
        $camera = Equipement::create(['nom' => 'Caméra PTZ', 'description' => 'Caméra pour visioconférence']);

        // 3. Salles
        $salle1 = Salle::create([
            'nom' => 'Salle de Réunion A',
            'capacite' => 15,
            'localisation' => 'Bâtiment Principal, 1er Étage',
            'description' => 'Idéale pour les réunions d\'équipe.',
            'disponible' => true,
            'responsable_id' => $admin->id,
        ]);
        $salle1->equipements()->attach([
            $projecteur->id => ['quantite' => 1],
            $tableau->id => ['quantite' => 1],
        ]);

        $salle2 = Salle::create([
            'nom' => 'Salle de Conférence B',
            'capacite' => 50,
            'localisation' => 'Bâtiment Nord, 2ème Étage',
            'description' => 'Grande salle avec tous les équipements audio/vidéo.',
            'disponible' => true,
            'responsable_id' => $responsable->id,
        ]);
        $salle2->equipements()->attach([
            $projecteur->id => ['quantite' => 2],
            $micro->id => ['quantite' => 4],
            $camera->id => ['quantite' => 1],
        ]);

        $salle3 = Salle::create([
            'nom' => 'Espace de Coworking',
            'capacite' => 8,
            'localisation' => 'Bâtiment Sud, RDC',
            'description' => 'Petit espace convivial pour les ateliers.',
            'disponible' => true,
            'responsable_id' => $responsable->id,
        ]);

        // 4. Plannings (Reservations)
        $today = Carbon::today();
        
        Planning::create([
            'salle_id' => $salle1->id,
            'user_id' => $user->id,
            'titre' => 'Réunion Mensuelle',
            'type_evenement' => 'Réunion',
            'date_debut' => $today->copy()->addHours(9),
            'date_fin' => $today->copy()->addHours(11),
            'statut' => 'approuve',
        ]);

        Planning::create([
            'salle_id' => $salle2->id,
            'user_id' => $user->id,
            'titre' => 'Formation Technique',
            'type_evenement' => 'Formation',
            'date_debut' => $today->copy()->addHours(14),
            'date_fin' => $today->copy()->addHours(18),
            'statut' => 'en_attente',
        ]);

        Planning::create([
            'salle_id' => $salle3->id,
            'user_id' => $responsable->id,
            'titre' => 'Atelier Design',
            'type_evenement' => 'Atelier',
            'date_debut' => $today->copy()->addDays(1)->addHours(10),
            'date_fin' => $today->copy()->addDays(1)->addHours(12),
            'statut' => 'approuve',
        ]);
        
        Planning::create([
            'salle_id' => $salle1->id,
            'user_id' => $user->id,
            'titre' => 'Présentation Projet Pro',
            'type_evenement' => 'Présentation client',
            'date_debut' => $today->copy()->addDays(2)->addHours(10),
            'date_fin' => $today->copy()->addDays(2)->addHours(12),
            'statut' => 'rejete',
        ]);
    }
}
