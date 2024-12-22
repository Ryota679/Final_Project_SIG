<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        // Jika sedang di halaman login atau register, lewati pengecekan
        if ($request->is('login') || $request->is('register') || $request->is('*/login') || $request->is('*/register')) {
            return $next($request);
        }

        // Jika tidak ada session yang aktif, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to continue.');
        }

        return $next($request);
    }
} 