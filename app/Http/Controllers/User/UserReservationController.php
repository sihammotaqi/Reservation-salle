<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planning;
use App\Models\Salle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\ReservationService;

class UserReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Planning::with('salle')->where('user_id', Auth::id());

        $validStatuts = [
            Planning::STATUT_EN_ATTENTE,
            Planning::STATUT_APPROUVE,
            Planning::STATUT_REJETE,
            Planning::STATUT_ANNULE,
        ];

        if ($request->has('statut') && in_array($request->statut, $validStatuts)) {
            $query->where('statut', $request->statut);
        }

        $reservations = $query->latest()->paginate(5);

        return view('user.reservations.index', compact('reservations'));
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

        return back()->with('success', 'Réservation créée');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
    public function destroy(Planning $planning)
    {
        if ($planning->user_id !== Auth::id()) {
            abort(403);
        }

        if ($planning->statut !== Planning::STATUT_EN_ATTENTE) {
            return back()->with('error', 'Vous ne pouvez annuler que les demandes en attente.');
        }

        // ✅ On met le statut à 'annule' au lieu de supprimer (garde l'historique)
        $planning->update(['statut' => Planning::STATUT_ANNULE]);

        return back()->with('success', 'Votre réservation a été annulée.');
    }
}

