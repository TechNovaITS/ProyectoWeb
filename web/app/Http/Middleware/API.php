<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class API
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.api.api_token');
        $provided = $request->bearerToken() ?? $request->header('X-API-Token');

        if (!is_string($expected) || $expected === '' || !is_string($provided) || !hash_equals($expected, $provided)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
