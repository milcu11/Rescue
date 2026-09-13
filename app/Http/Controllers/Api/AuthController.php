<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = Auth::guard('api')->user();

        if ($user->status !== 'active') {
            Auth::guard('api')->logout();

            return response()->json([
                'message' => 'Your account is inactive.',
            ], 403);
        }

        AuditService::login($user->name, $user);

        return response()->json([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role->slug,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = Auth::guard('api')->user();

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role->slug,
        ]);
    }

    public function logout()
    {
        $user = Auth::guard('api')->user();
        AuditService::logout($user->name, $user);
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
