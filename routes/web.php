<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\UtilisateurController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\EquipementController;

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
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile (any authenticated user)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('salles', SalleController::class);
    Route::resource('utilisateurs', UtilisateurController::class);
    Route::resource('planning', PlanningController::class);
    Route::resource('equipements', EquipementController::class)->except(['create', 'edit', 'show']);
});

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});