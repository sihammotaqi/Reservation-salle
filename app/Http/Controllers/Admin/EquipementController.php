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
        $equipements = Equipement::latest()->get();
        return view('admin.equipements.index', compact('equipements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantite'    => 'required|integer|min:0',
        ]);

        Equipement::create($request->only(['nom', 'description', 'quantite']));

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement ajouté.');
    }

    public function update(Request $request, Equipement $equipement)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantite'    => 'required|integer|min:0',
        ]);

        $equipement->update($request->only(['nom', 'description', 'quantite']));

        return redirect()->route('admin.equipements.index')->with('success', 'Équipement mis à jour.');
    }

    public function destroy(Equipement $equipement)
    {
        $equipement->delete();
        return redirect()->route('admin.equipements.index')->with('success', 'Équipement supprimé.');
    }
}
