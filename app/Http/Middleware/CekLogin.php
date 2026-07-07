<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        if (!session()->has('user_logged')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($role && session('user_role') !== $role) {

            if (session('user_role') === 'user') {
                return redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman Admin.');
            }

            if (session('user_role') === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Admin tidak diizinkan mengakses halaman ini.');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}