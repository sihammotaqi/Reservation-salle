<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalleController extends Controller
{
    public function index(Request $request)
{
    $query = Salle::with('equipements')->withCount(['plannings']);

    // 🔍 search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%$search%")
              ->orWhere('localisation', 'like', "%$search%");
        });
    }

    
    if ($request->filled('responsable_id')) {
        $query->where('responsable_id', $request->responsable_id);
    }

    $salles = $query->latest()->paginate(5)->withQueryString();

    
    $responsables = \App\Models\User::whereIn('role', ['admin', 'responsible'])->get();

    return view('admin.salles.index', compact('salles', 'responsables'));
}

    public function create()
    {
        $equipements = Equipement::all();
        $responsables = \App\Models\User::whereIn('role', ['admin', 'responsible'])->get();
        return view('admin.salles.create', compact('equipements', 'responsables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'           => 'required|string|max:255',
            'capacite'      => 'required|integer|min:1',
            'localisation'  => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'disponible'    => 'boolean',
            'responsable_id'=> 'nullable|exists:users,id',
            'equipements'   => 'nullable|array',
            'equipements.*' => 'integer|min:0',
        ]);

        // ✅ Vérification du stock avant création
        if ($request->has('equipements') && is_array($request->equipements)) {
            foreach ($request->equipements as $equipementId => $quantite) {
                if ($quantite > 0) {
                    $equipement = Equipement::find($equipementId);
                    if (!$equipement) continue;

                    $dejaUtilise = DB::table('equipement_salle')
                        ->where('equipement_id', $equipementId)
                        ->sum('quantite');

                    $disponible = $equipement->quantite - $dejaUtilise;

                    if ($quantite > $disponible) {
                        return back()->withInput()->withErrors([
                            "equipements.{$equipementId}" =>
                            "Stock insuffisant pour \"{$equipement->nom}\" : disponible {$disponible}, demandé {$quantite}."
                        ]);
                    }
                }
            }
        }

        $salle = Salle::create([
            'nom'         => $request->nom,
            'capacite'    => $request->capacite,
            'localisation'=> $request->localisation,
            'description' => $request->description,
            'responsable_id'=> $request->responsable_id,
            'disponible'  => $request->has('disponible'),
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
        $equipements = Equipement::all();
        $responsables = \App\Models\User::whereIn('role', ['admin', 'responsible'])->get();
        return view('admin.salles.edit', compact('salle', 'equipements', 'responsables'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'capacite'     => 'required|integer|min:1',
            'localisation' => 'nullable|string|max:255',
            'responsable_id'=> 'nullable|exists:users,id',
            'description'  => 'nullable|string',
        ]);

        // ✅ Vérification du stock avant mise à jour (en excluant la salle actuelle)
        if ($request->has('equipements') && is_array($request->equipements)) {
            foreach ($request->equipements as $equipementId => $quantite) {
                if ($quantite > 0) {
                    $equipement = Equipement::find($equipementId);
                    if (!$equipement) continue;

                    $dejaUtilise = DB::table('equipement_salle')
                        ->where('equipement_id', $equipementId)
                        ->where('salle_id', '!=', $salle->id)
                        ->sum('quantite');

                    $disponible = $equipement->quantite - $dejaUtilise;

                    if ($quantite > $disponible) {
                        return back()->withInput()->withErrors([
                            "equipements.{$equipementId}" =>
                            "Stock insuffisant pour \"{$equipement->nom}\" : disponible {$disponible}, demandé {$quantite}."
                        ]);
                    }
                }
            }
        }

        $salle->update([
            'nom'          => $request->nom,
            'capacite'     => $request->capacite,
            'localisation' => $request->localisation,
            'description'  => $request->description,
            'responsable_id'=> $request->responsable_id,
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
