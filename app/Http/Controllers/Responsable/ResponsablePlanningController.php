<?php
namespace App\Http\Controllers\Responsable;
use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\Planning;
use Carbon\Carbon;

class ResponsablePlanningController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Salles paginées pour le planning (5 par page)
        $salles = Salle::with(['plannings' => function ($q) use ($today) {
            $q->whereIn('statut', ['approuve', 'en_attente'])
            ->whereDate('date_debut', '<=', $today)
            ->whereDate('date_fin', '>=', $today)
            ->with('user')
            ->orderBy('date_debut');
        }])->where('disponible', true)->paginate(5);

        // Toutes les salles pour le select du modal (pas paginées)
        $allSalles = Salle::where('disponible', true)->get();

        $totalSalles       = Salle::where('disponible', true)->count();
        $reservationsToday = Planning::whereDate('date_debut', $today)
            ->whereIn('statut', ['approuve', 'en_attente'])->count();
        $tauxOccupation    = $totalSalles > 0
            ? round(($reservationsToday / $totalSalles) * 100)
            : 0;

        return view('responsable.salles', compact(
            'salles', 'allSalles', 'today', 'totalSalles', 'reservationsToday', 'tauxOccupation'
        ));
    }
}
