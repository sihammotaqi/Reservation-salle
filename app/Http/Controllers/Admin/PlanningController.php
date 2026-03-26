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
    $date = $request->input('date', now()->toDateString());

    $salles =Salle::with(['plannings' => function ($q) use ($date) {
        $q->whereDate('date_debut', '<=', $date)
          ->whereDate('date_fin', '>=', $date);
    }])->paginate(5);

    return view('admin.planning.index', compact('salles', 'date'));
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

        Planning::create(
            $request->only(['salle_id', 'user_id', 'titre', 'date_debut', 'date_fin'])
            + ['statut' => 'en_attente']
        );

        return redirect()->route('admin.planning.index')->with('success', 'Réservation créée.');
    }

    public function update(Request $request, Planning $planning)
    {
        $request->validate(['statut' => 'required|in:en_attente,approuve,rejete,annule']);
        $planning->update(['statut' => $request->statut]);
        return redirect()->route('admin.planning.index')->with('success', 'Statut mis à jour.');
    }

    public function destroy(Planning $planning)
    {
        $planning->delete();
        return redirect()->route('admin.planning.index')->with('success', 'Réservation supprimée.');
    }
}
