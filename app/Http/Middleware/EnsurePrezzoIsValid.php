<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrezzoIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!isset($request->prezzo)) {
            return response()->json([
                'code' => 'missing_param',
                'message' => 'Il parametro \'prezzo\' è obbligatorio'
            ], 400);
        } elseif ($request->prezzo != (int) $request->prezzo) {
            return response()->json([
                'code' => 'bad_param',
                'message' => 'Il parametro \'prezzo\' deve essere un numero intero'
            ], 400);
        } elseif ($request->prezzo < 1) {
            return response()->json([
                'code' => 'invalid_price',
                'message' => 'Il parametro \'prezzo\' deve essere maggiore di 0'
            ], 404);
        }
        return $next($request);
    }
}
