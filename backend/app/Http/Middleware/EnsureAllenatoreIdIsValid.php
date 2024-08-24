<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Storage;

class EnsureAllenatoreIdIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!isset($request->id_allenatore)) {
            return response()->json([
                'code' => 'missing_param',
                'message' => 'Il parametro \'id_allenatore\' è obbligatorio'
            ], 400);
        } elseif(!is_numeric($request->id_allenatore) || $request->id_allenatore != (int)$request->id_allenatore) {
            return response()->json([
                'code' => 'invalid_param',
                'message' => 'Il parametro \'id_allenatore\' deve essere un numero intero'
            ], 400);
        } else {
            $giocatori = Storage::json('public/allenatori.json');
            if(!in_array($request->id_allenatore, array_column($giocatori, 'Id'))) {
                return response()->json([
                    'code' => 'not_found',
                    'message' => 'Allenatore non trovato'
                ], 404);
            }
        }
        return $next($request);
    }
}
