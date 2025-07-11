<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Allow public access to complaint creation
        if ($request->is('complaints/create') || $request->is('complaints')) {
            return null;
        }
        
        return $request->expectsJson() ? null : '/home';
    }
}
