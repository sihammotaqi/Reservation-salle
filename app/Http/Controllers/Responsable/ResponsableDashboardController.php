<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Planning;
use Illuminate\Support\Facades\Auth;
use App\Models\Salle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ReservationService;

class ResponsableDashboardController extends Controller
{
    public function index()
    {
        // Stats
        $enAttente = Planning::where('statut', 'en_attente')
    ->whereHas('salle', function ($q) {
        $q->where('responsable_id', auth()->id());
    })
    ->count();
        $validations = Planning::where('statut', 'approuve')
    ->whereMonth('updated_at', Carbon::now()->month)
    ->whereHas('salle', function ($q) {
        $q->where('responsable_id', auth()->id());
    })
    ->count();
        $refus = Planning::where('statut', 'rejete')
    ->whereMonth('updated_at', Carbon::now()->month)
    ->whereHas('salle', function ($q) {
        $q->where('responsable_id', auth()->id());
    })
    ->count();
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
    ->whereHas('salle', function ($q) {
        $q->where('responsable_id', auth()->id());
    })
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
    public function store(Request $request, ReservationService $service)
    {
    $request->validate([
        'salle_id'       => 'required|exists:salles,id',
        'titre'          => 'required|string|max:255',
        'type_evenement' => 'required|string|max:255',
        'date_debut'     => 'required|date|after_or_equal:now',
        'date_fin'       => 'required|date|after:date_debut',
    ]);

    try {
        $service->create([
            'salle_id'       => $request->salle_id,
            'user_id'        => auth()->id(),
            'titre'          => $request->titre,
            'type_evenement' => $request->type_evenement,
            'date_debut'     => $request->date_debut,
            'date_fin'       => $request->date_fin,
            'statut'         => 'en_attente',
        ]);

        return redirect()->route('responsable.salles.index')
            ->with('success', 'Réservation créée');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
    }
}
