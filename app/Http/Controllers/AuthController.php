<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth.auth-page');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|min:3|max:150',
                'email'        => 'required|email:rfc,dns|max:254|unique:users,email',
                'divisi'       => 'required|string|max:150',
                'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
                'password'     => [
                    'required', 
                    'string', 
                    'confirmed', 
                    Password::min(8)
                        ->letters()
                        ->numbers()
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        }

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
        try {
            $request->validate([
                'Email'    => 'required|email:rfc,dns',
                'password' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        }

        $user = Users::where('email', $request->Email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $request->session()->regenerate();

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