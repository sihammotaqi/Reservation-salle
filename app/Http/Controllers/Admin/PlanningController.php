<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Planning;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index()
    {
        $plannings = Planning::with(['salle', 'user'])->latest()->get();
        return view('admin.planning.index', compact('plannings'));
    }

    public function create()
    {
        $salles = Salle::where('disponible', true)->get();
        $utilisateurs = User::all();
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

        Planning::create($request->only(['salle_id', 'user_id', 'titre', 'date_debut', 'date_fin']) + ['statut' => 'en_attente']);

        return redirect()->route('admin.planning.index')->with('success', 'Réservation créée.');
    }

    public function update(Request $request, Planning $planning)
    {
        $request->validate(['statut' => 'required|in:en_attente,approuve,rejete']);
        $planning->update(['statut' => $request->statut]);
        return redirect()->route('admin.planning.index')->with('success', 'Statut mis à jour.');
    }

    public function destroy(Planning $planning)
    {
        $planning->delete();
        return redirect()->route('admin.planning.index')->with('success', 'Réservation supprimée.');
    }
}
