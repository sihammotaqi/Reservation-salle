<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = User::latest()->paginate(10);
        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        return view('admin.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:user,admin,responsible',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make('password'), // Default password
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès. Le mot de passe par défaut est "password".');
    }

    public function edit(User $utilisateur)
    {
        return view('admin.utilisateurs.edit', compact('utilisateur'));
    }

    public function update(Request $request, User $utilisateur)
    {
        $request->validate([
            'role'  => 'required|in:user,admin,responsible',
        ]);

        $utilisateur->update([
            'role'  => $request->role,
        ]);

        return redirect()->route('admin.utilisateurs.index')->with('success', 'Rôle de l\'utilisateur mis à jour.');
    }

    public function destroy(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return redirect()->route('admin.utilisateurs.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $utilisateur->delete();
        return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur supprimé.');
    }
}
