<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;


class EnsureJsonsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $giocatori = Storage::json('public/giocatori.json');
        $allenatori = Storage::json('public/allenatori.json');

        if(!isset($giocatori) || !isset($allenatori)) {
            return response()->json([
                'code' => 'not_found',
                'message' => 'File Json non trovati'
            ], 404);
        }
        return $next($request);
    }
}
