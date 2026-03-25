<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResponsableMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['responsible', 'admin'])) {
            return redirect()->route('dashboard')->with('error', 'Accès réservé aux responsables.');
        }

        return $next($request);
    }
}