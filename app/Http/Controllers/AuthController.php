<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth.auth-page');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email'        => 'required|email|max:100|unique:users,email',
            'divisi'       => 'required|string|max:100',
            'no_telp'      => 'required|string|max:20',
            'password'     => 'required|string|min:4|max:20'
        ]);

        Users::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'divisi'       => $request->divisi,
            'no_telp'      => $request->no_telp,
            'password'     => Hash::make($request->password),
            'role'         => 'user'          
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan login.'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = Users::where('email', $request->Email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $request->session()->put([
            'user_id'      => $user->id,
            'user_logged'  => $user->email,
            'nama_lengkap' => $user->nama_lengkap,
            'user_role'    => $user->role
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

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}