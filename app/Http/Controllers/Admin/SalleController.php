<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::with('equipements')->withCount(['plannings'])->latest()->get();
        return view('admin.salles.index', compact('salles'));
    }

    public function create()
    {
        $equipements = \App\Models\Equipement::all();
        return view('admin.salles.create', compact('equipements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'capacite'     => 'required|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'disponible'   => 'boolean',
            'equipements'  => 'nullable|array',
            'equipements.*'=> 'integer|min:0'
        ]);

        $salle = Salle::create([
            'nom'          => $request->nom,
            'capacite'     => $request->capacite,
            'localisation' => $request->localisation,
            'description'  => $request->description,
            'disponible'   => $request->has('disponible'),
        ]);

        if ($request->has('equipements') && is_array($request->equipements)) {
            $syncData = [];
            foreach ($request->equipements as $equipementId => $quantite) {
                if ($quantite > 0) {
                    $syncData[$equipementId] = ['quantite' => $quantite];
                }
            }
            $salle->equipements()->attach($syncData);
        }

        return redirect()->route('admin.salles.index')->with('success', 'Salle créée avec succès.');
    }

    public function edit(Salle $salle)
    {
        $equipements = \App\Models\Equipement::all();
        return view('admin.salles.edit', compact('salle', 'equipements'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'capacite'     => 'required|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'description'  => 'nullable|string',
        ]);

        $salle->update([
            'nom'          => $request->nom,
            'capacite'     => $request->capacite,
            'localisation' => $request->localisation,
            'description'  => $request->description,
            'disponible'   => $request->has('disponible'),
        ]);

        if ($request->has('equipements') && is_array($request->equipements)) {
            $syncData = [];
            foreach ($request->equipements as $equipementId => $quantite) {
                if ($quantite > 0) {
                    $syncData[$equipementId] = ['quantite' => $quantite];
                }
            }
            $salle->equipements()->sync($syncData);
        } else {
            // Optionnel: vider si on envoie rien, mais généralement on a des inputs cachés ou des valeurs à 0.
            $salle->equipements()->detach();
        }

        return redirect()->route('admin.salles.index')->with('success', 'Salle mise à jour.');
    }

    public function destroy(Salle $salle)
    {
        $salle->delete();
        return redirect()->route('admin.salles.index')->with('success', 'Salle supprimée.');
    }
}
