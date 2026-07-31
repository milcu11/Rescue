<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        if (!in_array($user->role->slug, $roles)) {
            if ($user->role->slug === 'donor') {
                return redirect()->route('donor.index')
                    ->with('error', 'Donor accounts can only access the donor portal.');
            }

            if ($user->role->slug === 'volunteer') {
                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Volunteers are not permitted to access this section.');
            }

            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
