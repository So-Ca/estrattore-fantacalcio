<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class EnsureSandboxJsonsExist
{
    public function handle(Request $request, Closure $next): Response
    {
        $giocatori = Storage::json('public/giocatori_sandbox.json');
        $allenatori = Storage::json('public/allenatori_sandbox.json');

        if (!isset($giocatori) || !isset($allenatori)) {
            return response()->json([
                'code' => 'not_found',
                'message' => 'File Json sandbox non trovati'
            ], 404);
        }
        return $next($request);
    }
}
