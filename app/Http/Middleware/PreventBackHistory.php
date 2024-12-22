<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        return $response->header('Cache-Control','no-store,no-cache,must-revalidate,max-age=0')
            ->header('Pragma','no-cache')
            ->header('Expires','Sat, 01 Jan 1990 00:00:00 GMT');
    }
} 