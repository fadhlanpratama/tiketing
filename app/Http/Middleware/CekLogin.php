<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Users;

class CekLogin
{
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        $userId = session('user_id');
        $user = $userId ? Users::find($userId) : null;

        if (!$user || $user->status !== 'active') {
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($role && $user->role !== $role) {

            if ($user->role === 'user') {
                return redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Admin tidak diizinkan mengakses halaman ini.');
            }

            if ($user->role === 'pj') {
                return redirect()->route('pj.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman ini.');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}