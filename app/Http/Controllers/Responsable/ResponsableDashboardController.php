<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Planning;
use Illuminate\Support\Facades\Auth;
use App\Models\Salle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResponsableDashboardController extends Controller
{
    public function index()
    {
        // Stats
        $enAttente   = Planning::where('statut', 'en_attente')->count();
        $validations = Planning::where('statut', 'approuve')
                               ->whereMonth('updated_at', Carbon::now()->month)->count();
        $refus       = Planning::where('statut', 'rejete')
                               ->whereMonth('updated_at', Carbon::now()->month)->count();
        $totalMois   = $validations + $refus;
        $tauxRefus   = $totalMois > 0 ? round(($refus / $totalMois) * 100) : 0;

        // Validations mois dernier (pour le +%)
        $validationsDernierMois = Planning::where('statut', 'approuve')
            ->whereMonth('updated_at', Carbon::now()->subMonth()->month)->count();
        $evolutionValidations = $validationsDernierMois > 0
            ? round((($validations - $validationsDernierMois) / $validationsDernierMois) * 100)
            : 0;

        // Demandes en attente
        $demandes = Planning::with(['salle', 'user'])
            ->where('statut', 'en_attente')
            ->orderBy('date_debut', 'asc')
            ->paginate(5);

        return view('responsable.dashboard', compact(
            'enAttente',
            'validations',
            'refus',
            'tauxRefus',
            'evolutionValidations',
            'demandes'
        ));
    }

    public function approuver(Planning $planning)
    {
        if ($planning->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        $planning->update(['statut' => 'approuve']);

        return back()->with('success', 'Réservation approuvée avec succès.');
    }

    public function rejeter(Request $request, Planning $planning)
    {
        if ($planning->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande ne peut plus être modifiée.');
        }

        $planning->update(['statut' => 'rejete']);

        return back()->with('success', 'Réservation refusée.');
    }
    public function store(Request $request)
    {
    $request->validate([
        'salle_id'       => 'required|exists:salles,id',
        'titre'          => 'required|string|max:255',
        'type_evenement' => 'required|string|max:255',
        'date_debut'     => 'required|date|after_or_equal:now',
        'date_fin'       => 'required|date|after:date_debut',
    ]);

    $salle = Salle::findOrFail($request->salle_id);

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
        return back()->with('error', 'Conflit d\'horaire détecté.');
    }

    Planning::create([
        'salle_id'       => $request->salle_id,
        'user_id'        => Auth::id(),
        'titre'          => $request->titre,
        'type_evenement' => $request->type_evenement,
        'date_debut'     => Carbon::parse($request->date_debut),
        'date_fin'       => Carbon::parse($request->date_fin),
        'statut'         => 'en_attente',
    ]);

    return redirect()->route('responsable.salles.index')
        ->with('success', 'Demande de réservation envoyée.');
    }
}
