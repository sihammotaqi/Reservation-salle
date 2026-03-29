<?php
namespace App\Http\Controllers\Responsable;
use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\Planning;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResponsablePlanningController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $selectedDate = $request->filled('date') ? Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay() : $today;

        // Salles paginées pour le planning (5 par page)
        $salles = Salle::where('responsable_id', auth()->id())
    ->with(['plannings' => function ($q) use ($selectedDate) {
        $q->whereIn('statut', ['approuve', 'en_attente'])
          ->whereDate('date_debut', $selectedDate->toDateString())
          ->with('user')
          ->orderBy('date_debut');
    }])->paginate(5)->appends($request->query());

        // Toutes les salles pour le select du modal (pas paginées)
        $allSalles = Salle::where('disponible', true)->get();

        $totalSalles       = Salle::where('disponible', true)->count();
        $reservationsToday = Planning::whereDate('date_debut', $today)
            ->whereIn('statut', ['approuve', 'en_attente'])->count();
        $tauxOccupation    = $totalSalles > 0
            ? round(($reservationsToday / $totalSalles) * 100)
            : 0;

        return view('responsable.salles', compact(
            'salles', 'allSalles', 'today', 'selectedDate', 'totalSalles', 'reservationsToday', 'tauxOccupation'
        ));
    }

    public function list(Request $request)
    {
        $query = Planning::whereHas('salle', function ($q) {
                $q->where('responsable_id', auth()->id());
            })
            ->with(['salle', 'user']);

        if ($request->filled('statut') && in_array($request->statut, ['en_attente', 'approuve', 'rejete', 'annule'])) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('salle_id')) {
            $query->where('salle_id', $request->salle_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date_debut', $request->date);
        }

        $plannings = $query->orderBy('date_debut', 'desc')->paginate(5)->appends($request->query());

        $salles = Salle::where('responsable_id', auth()->id())->orderBy('nom')->get();

        return view('responsable.list', compact('plannings', 'salles'));
    }
}
