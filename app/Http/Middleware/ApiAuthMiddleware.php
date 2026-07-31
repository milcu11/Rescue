<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $exception) {
            return response()->json([
                'message' => 'Token expired. Please log in again.',
            ], 401);
        } catch (TokenInvalidException $exception) {
            return response()->json([
                'message' => 'Token is invalid.',
            ], 401);
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Authorization token not found.',
            ], 401);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Your account is inactive.',
            ], 403);
        }

        auth()->guard('api')->setUser($user);

        return $next($request);
    }
}
