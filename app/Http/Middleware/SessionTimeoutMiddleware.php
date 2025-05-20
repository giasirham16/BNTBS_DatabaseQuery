<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity', time());
            $expiresIn = Session::get('expires_in', 3600); // Default 1 jam

            if ((time() - $lastActivity) > $expiresIn) {
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();
                return redirect()->route('login')->with('error', 'Sesi Anda telah habis.');
            }

            // Perbarui last activity
            Session::put('last_activity', time());
        }

        return $next($request);
    }
}
