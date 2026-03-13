<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Salle;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::with('salle')->latest()->get();
        $salles = Salle::all();
        return view('admin.equipements.index', compact('equipements', 'salles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
            'salle_id' => 'nullable|exists:salles,id',
        ]);

        Equipement::create($request->only(['nom', 'quantite', 'salle_id']));

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement ajouté.');
    }

    public function update(Request $request, Equipement $equipement)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'quantite' => 'required|integer|min:1',
            'salle_id' => 'nullable|exists:salles,id',
        ]);

        $equipement->update($request->only(['nom', 'quantite', 'salle_id']));

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement mis à jour.');
    }

    public function destroy(Equipement $equipement)
    {
        $equipement->delete();
        return redirect()->route('admin.equipements.index')->with('success', 'Équipement supprimé.');
    }
}
