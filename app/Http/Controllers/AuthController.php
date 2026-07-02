<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCustom;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth.auth-page');
    }

    // ================= REGISTER =================
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|max:25|unique:user,username',
            'password' => 'required|string|min:4|max:20'
        ]);

        UserCustom::create([
            'username' => $request->username,
            'password' => $request->password,
            'role'     => 'user'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.'
        ]);
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = UserCustom::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.'
            ], 401);
        }

        $request->session()->put([
            'user_logged' => $user->username,
            'user_role'   => $user->role
        ]);
        
        $request->session()->save();

        $redirectUrl = ($user->role === 'admin') 
            ? route('admin.dashboard') 
            : route('user.dashboard');

        return response()->json([
            'success'  => true,
            'message'  => 'Login berhasil.',
            'redirect' => $redirectUrl
        ]);
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}