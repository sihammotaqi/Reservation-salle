<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::withCount(['equipements', 'plannings'])->latest()->get();
        return view('admin.salles.index', compact('salles'));
    }

    public function create()
    {
        return view('admin.salles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'        => 'required|string|max:255',
            'capacite'   => 'required|integer|min:1',
            'description'=> 'nullable|string',
            'disponible' => 'boolean',
        ]);

        Salle::create([
            'nom'         => $request->nom,
            'capacite'    => $request->capacite,
            'description' => $request->description,
            'disponible'  => $request->has('disponible'),
        ]);

        return redirect()->route('admin.salles.index')->with('success', 'Salle créée avec succès.');
    }

    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom'        => 'required|string|max:255',
            'capacite'   => 'required|integer|min:1',
            'description'=> 'nullable|string',
        ]);

        $salle->update([
            'nom'         => $request->nom,
            'capacite'    => $request->capacite,
            'description' => $request->description,
            'disponible'  => $request->has('disponible'),
        ]);

        return redirect()->route('admin.salles.index')->with('success', 'Salle mise à jour.');
    }

    public function destroy(Salle $salle)
    {
        $salle->delete();
        return redirect()->route('admin.salles.index')->with('success', 'Salle supprimée.');
    }
}
