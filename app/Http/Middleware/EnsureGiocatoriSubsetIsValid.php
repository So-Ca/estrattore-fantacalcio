<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGiocatoriSubsetIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(isset($request->tipo) && !($request->tipo === 'estratti' || $request->tipo === 'non-estratti')) {
            return response()->json([
                'code' => 'invalid_param',
                'message' => 'Il parametro \'tipo\', se presente, deve essere \'estratti\' o \'non-estratti\''
            ], 404);
        }
        return $next($request);
    }
}
