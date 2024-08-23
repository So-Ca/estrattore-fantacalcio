<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Storage;

class EnsureGiocatoreIdIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!is_numeric($request->id) || $request->id != (int)$request->id) {
            return response()->json([
                'code' => 'invalid_param',
                'message' => 'Il parametro \'id\' deve essere un numero intero'
            ], 400);
        } else {
            $giocatori = Storage::json('public/giocatori.json');
            if(!in_array($request->id, array_column($giocatori, 'Id'))) {
                return response()->json([
                    'code' => 'not_found',
                    'message' => 'Giocatore non trovato'
                ], 404);
            }
        }
        return $next($request);
    }
}
