<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salle;
use App\Models\Planning;

class UserSalleController extends Controller
{
    public function index()
    {
        $salles = Salle::with('equipements')->where('disponible', 1)->get();
        return view('user.salles.index', compact('salles'));
    }

    public function show(Salle $salle)
    {
        if (!$salle->disponible) {
            abort(404, 'Salle non disponible');
        }

        $salle->load('equipements');
        
        $plannings = Planning::where('salle_id', $salle->id)
            ->where('statut', 'approuve')
            ->where('date_fin', '>=', now())
            ->orderBy('date_debut', 'asc')
            ->get();

        return view('user.salles.show', compact('salle', 'plannings'));
    }
}
