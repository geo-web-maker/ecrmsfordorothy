<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSplashSeen
{
    /**
     * Redirect first-time visitors (per session) to the splash screen.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }
    
        if ($request->routeIs('splash', 'splash.continue', 'admin.login', 'login', 'register')) {
            return $next($request);
        }
    
        if ($request->session()->get('splash_seen')) {
            return $next($request);
        }
    
        $request->session()->put('splash_intended_url', $request->fullUrl());
    
        return redirect()->route('splash');
    }
}
