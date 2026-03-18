<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        //check if user loged + if he is an admin
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        //if not an admin show him erreur 403 (Forbidden)
        return response()->json([
            'message' => 'Accès refusé. Réservé aux administrateurs.'
        ], 403);
    }
}