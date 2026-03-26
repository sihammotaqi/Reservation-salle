<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salle;
use App\Models\Planning;
use Carbon\Carbon;

class UserSalleController extends Controller
{
    public function index()
    {
        $now   = now();
        $today = Carbon::today();

        $salles = Salle::with(['equipements', 'plannings' => function ($q) use ($today) {
            $q->whereIn('statut', ['approuve', 'en_attente'])
              ->whereDate('date_debut', $today)
              ->orderBy('date_debut');
        }])->where('disponible', true)->paginate(5);

        return view('user.salles.index', compact('salles', 'now'));
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
