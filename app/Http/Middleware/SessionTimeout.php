<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        $timeout = config('session.lifetime') * 60; // Convert minutes to seconds

        if (Auth::check()) {
            $lastActivity = Session::get('lastActivityTime');
            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                Auth::logout();
                Session::flush();
                return redirect('/home')->withErrors(['message' => 'You have been logged out due to inactivity.']);
            }

            Session::put('lastActivityTime', time());
        }

        return $next($request);
    }
}
