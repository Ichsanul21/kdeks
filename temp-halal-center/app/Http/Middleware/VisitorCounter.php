<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorCounter
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = \App\Models\SiteSetting::first();
        if ($setting) {
            $setting->increment('visitor_count', 3);
        }

        return $next($request);
    }
}
