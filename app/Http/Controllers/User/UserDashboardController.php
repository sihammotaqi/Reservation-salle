<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planning;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalReservations = Planning::where('user_id', $userId)->count();
        $approvedReservations = Planning::where('user_id', $userId)->where('statut', 'approuve')->count();
        $pendingReservations = Planning::where('user_id', $userId)->where('statut', 'en_attente')->count();

        $recentReservations = Planning::with('salle')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalReservations',
            'approvedReservations',
            'pendingReservations',
            'recentReservations'
        ));
    }
}
