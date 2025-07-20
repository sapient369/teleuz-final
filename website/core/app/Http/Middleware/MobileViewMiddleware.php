<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MobileViewMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $isMobile = isMobile();
        View::share('layout', $isMobile ? 'layouts.mobile' : 'layouts.app');
        View::share('isMobile', $isMobile);
        return $next($request);
    }
}