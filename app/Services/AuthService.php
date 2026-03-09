<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register($request)
    {
        dd($request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        Auth::login($user);

        return redirect('/dashboard');

    }



    public function login($request)
    {

        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            return redirect('/dashboard');

        }

        return back()->with('error','Invalid email or password');

    }

}