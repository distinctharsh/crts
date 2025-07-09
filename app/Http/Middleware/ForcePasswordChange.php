<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
    
        if ($user && $user->must_change_password) {
            // Check if current route is allowed
            $allowedRoutes = [
                'profile.change-password',   // Named route
                'logout',                    // Named route
            ];
    
            $allowedUris = [
                'profile/change-password',   // In case of direct URI match
            ];
    
            if (
                !in_array($request->route()->getName(), $allowedRoutes) &&
                !$request->is($allowedUris)
            ) {
                return redirect()->route('profile.change-password');
            }
        }
    
        return $next($request);
    }
    
} 