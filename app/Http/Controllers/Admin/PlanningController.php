<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Planning;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlanningController extends Controller
{
    public function index(Request $request)
{
    $today = Carbon::today();
    $view  = $request->get('view', 'timeline');

    // ✅ هذا هو الفلتر الحقيقي (بلا ما تبدل today)
    $selectedDate = $request->filled('date')
    ? Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay()
    : $today;

    // Timeline
    $salles = Salle::with(['plannings' => function ($q) use ($selectedDate) {
        $q->whereIn('statut', ['approuve', 'en_attente', 'rejete'])
          ->whereDate('date_debut', $selectedDate->toDateString())
          ->with('user')
          ->orderBy('date_debut');
    }])
    ->where('disponible', true)
    ->paginate(5)
    ->appends(request()->query());

    // Liste
    $plannings = Planning::with(['salle', 'user'])
        ->latest()
        ->paginate(5);

    return view('admin.planning.index', compact(
        'plannings',
        'salles',
        'today',
        'selectedDate',
        'view'
    ));
}

    public function create()
    {
        $salles = Salle::where('disponible', true)->get();
        $utilisateurs = [\Illuminate\Support\Facades\Auth::user()];
        return view('admin.planning.create', compact('salles', 'utilisateurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salle_id'   => 'required|exists:salles,id',
            'user_id'    => 'required|exists:users,id',
            'titre'      => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after:date_debut',
        ]);

        // ✅ Conflict check
        $conflict = Planning::where('salle_id', $request->salle_id)
            ->whereIn('statut', ['en_attente', 'approuve'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('date_debut', [$request->date_debut, $request->date_fin])
                  ->orWhereBetween('date_fin', [$request->date_debut, $request->date_fin])
                  ->orWhere(function ($q) use ($request) {
                      $q->where('date_debut', '<=', $request->date_debut)
                        ->where('date_fin', '>=', $request->date_fin);
                  });
            })->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Conflit d\'horaire détecté. Cette salle est déjà réservée ou en attente sur ce créneau.');
        }

        Planning::create([
    'salle_id'   => $request->salle_id,
    'user_id'    => auth()->id(),
    'titre'      => $request->titre,
    'date_debut' => $request->date_debut,
    'date_fin'   => $request->date_fin,
    'statut'     => 'en_attente',
     ]);

        return redirect()->route('admin.planning.index')->with('success', 'Réservation créée.');
    }

    public function update(Request $request, Planning $planning)
    {
        $request->validate(['statut' => 'required|in:en_attente,approuve,rejete,annule']);
        $planning->update(['statut' => $request->statut]);
        return redirect()->route('admin.planning.index')->with('success', 'Statut mis à jour.');
    }
    public function list(Request $request)
    {
        $query = Planning::with(['salle', 'user']);

        if ($request->filled('responsable_id')) {
            $query->whereHas('salle', function ($q) use ($request) {
                $q->where('responsable_id', $request->responsable_id);
            });
        }

        if ($request->filled('salle_id')) {
            $query->where('salle_id', $request->salle_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date_debut', $request->date);
        }

        $plannings = $query->orderBy('date_debut', 'desc')->paginate(5)->appends($request->query());

        $salles = Salle::orderBy('nom')->get();
        $responsables = User::where('role', 'responsible')->orderBy('name')->get();

        return view('admin.planning.list', compact('plannings', 'salles', 'responsables'));
    }

    public function destroy(Planning $planning)
    {
        $planning->delete();
        return redirect()->route('admin.planning.index')->with('success', 'Réservation supprimée.');
    }
}
