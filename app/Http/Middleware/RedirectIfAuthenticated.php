<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('user_logged')) {
            $role = session('user_role');

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($role === 'pj') {
                return redirect()->route('pj.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}