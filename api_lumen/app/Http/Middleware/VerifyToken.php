<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;

class VerifyToken
{
    public function handle($request, Closure $next)
    {
        if (($token = $request->bearerToken())) {
            try {
                JWT::decode($token, $_ENV['JWT_KEY'], ['HS384']);
            } catch (\Firebase\JWT\ExpiredException $e) {
                return (new Response('Unauthorized.', 401))
                    ->header('Content-Type', 'application/json');
            }

            return $next($request);
        }

        return (new Response('Unauthorized.', 401))
            ->header('Content-Type', 'application/json');
    }
}
