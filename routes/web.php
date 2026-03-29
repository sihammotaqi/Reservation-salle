<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\EquipementController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserSalleController;
use App\Http\Controllers\User\UserReservationController;
use App\Http\Controllers\Responsable\ResponsableDashboardController;
use App\Http\Controllers\Responsable\ResponsablePlanningController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return view('dashboard');
        }
        if (Auth::user()->role === 'responsible') {
        return redirect()->route('responsable.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile (any authenticated user)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User Portal Routes (Regular users & responsibles)
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/salles', [UserSalleController::class, 'index'])->name('salles.index');
        Route::get('/salles/{salle}', [UserSalleController::class, 'show'])->name('salles.show');
        Route::get('/reservations', [UserReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations', [UserReservationController::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{planning}', [UserReservationController::class, 'destroy'])->name('reservations.destroy');
    });
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('salles', SalleController::class);
    Route::resource('utilisateurs', UtilisateurController::class);
    
    // Custom routes for planning must come before the resource route
    Route::get('/planning/list', [PlanningController::class, 'list'])->name('planning.list');
    Route::resource('planning', PlanningController::class);

    Route::resource('equipements', EquipementController::class)->except(['create', 'edit', 'show']);
});

Route::middleware(['auth', 'responsable'])->prefix('responsable')->name('responsable.')->group(function () {
    Route::get('/dashboard', [ResponsableDashboardController::class, 'index'])->name('dashboard');
    Route::post('/reservations/{planning}/approuver', [ResponsableDashboardController::class, 'approuver'])->name('reservations.approuver');
    Route::post('/reservations/{planning}/rejeter', [ResponsableDashboardController::class, 'rejeter'])->name('reservations.rejeter');
    Route::get('/salles/list', [ResponsablePlanningController::class, 'list'])->name('salles.list');
    Route::get('/salles', [ResponsablePlanningController::class, 'index'])->name('salles.index');
    Route::post('/reservations', [ResponsableDashboardController::class, 'store'])->name('reservations.store');
});


// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});