<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planning;
use App\Models\Salle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function store(Request $request)
    {
        $request->validate([
            'salle_id'        => 'required|exists:salles,id',
            'titre'           => 'required|string|max:255',
            'type_evenement'  => 'required|string|max:255',
            'date_debut'      => 'required|date|after_or_equal:now',
            'date_fin'        => 'required|date|after:date_debut',
        ]);

        $salle = Salle::findOrFail($request->salle_id);

        if (!$salle->disponible) {
            return back()->with('error', 'Cette salle n\'est pas disponible.')->withInput();
        }

        // ✅ FIX: Vérifier conflits avec PENDING + APPROVED (pas seulement approved)
        $conflict = Planning::where('salle_id', $request->salle_id)
            ->whereIn('statut', [Planning::STATUT_EN_ATTENTE, Planning::STATUT_APPROUVE])
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_debut', [$request->date_debut, $request->date_fin])
                      ->orWhereBetween('date_fin', [$request->date_debut, $request->date_fin])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('date_debut', '<=', $request->date_debut)
                            ->where('date_fin', '>=', $request->date_fin);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()
                ->with('error', 'Conflit d\'horaire détecté. Cette salle est déjà réservée ou en attente sur ce créneau. Veuillez choisir un autre créneau ou une autre salle.')
                ->withInput();
        }

        Planning::create([
            'salle_id'       => $request->salle_id,
            'user_id'        => Auth::id(),
            'titre'          => $request->titre,
            'type_evenement' => $request->type_evenement,
            'date_debut'     => Carbon::parse($request->date_debut),
            'date_fin'       => Carbon::parse($request->date_fin),
            'statut'         => Planning::STATUT_EN_ATTENTE,
        ]);

        return redirect()->route('user.reservations.index')
            ->with('success', 'Votre demande de réservation a été envoyée et est en attente de validation.');
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

