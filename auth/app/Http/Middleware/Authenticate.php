<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use Closure;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if(!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));
        } catch(ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 403);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 403);
        }

        return $next($request);
    }
}
