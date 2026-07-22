<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

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
                'nama_lengkap' => ['required', 'string', 'min:3', 'max:150'],
                'email'        => 'required|email:rfc,dns|max:254|unique:users,email',
                'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
                'password'     => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()],
            ], [
                'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        }

        // Simpan data registrasi baru
        Users::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'no_telp'      => $request->no_telp,
            'password'     => $request->password,
            'divisi'       => null,  
            'role'         => 'user', 
            'status'       => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Akun sedang ditinjau Admin.'
        ]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string'
            ], [
                'email.email'       => 'Format email tidak valid.',
                'email.required'    => 'Email wajib diisi.',
                'password.required' => 'Password wajib diisi.'
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            $field = $errors->has('email') ? 'email_login' : ($errors->has('password') ? 'password_login' : null);

            return response()->json([
                'success' => false,
                'field'   => $field,
                'message' => $errors->first()
            ], 422);
        }


        $throttleKey = 'login|' . $request->ip();
        $lockKey = 'login_lock:' . $throttleKey;

        // 1. Cek Rate Limiter jika over dari 5 ke blok 1 menit
        if (Cache::has($lockKey)) {
            $seconds = Cache::get($lockKey) - time();

            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik lagi."
            ], 429);
        }

        $user = Users::where('email', $request->email)->first();

        // 2. Cek email apakah terdaftar
        if (!$user) {

        RateLimiter::hit($throttleKey);

        if (RateLimiter::attempts($throttleKey) >= 5) {

                Cache::put($lockKey, time() + 180, 180);
                RateLimiter::clear($throttleKey);

                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan login. Silakan tunggu 180 detik lagi.'
                ], 429);
            }

            return response()->json([
                'success' => false,
                'field'   => 'email_login',
                'message' => 'Alamat email belum terdaftar.'
            ], 401);
        }
        
        // 3. Cek Kebenaran Password
        if (!Hash::check($request->password, $user->password)) {

            RateLimiter::hit($throttleKey);

            if (RateLimiter::attempts($throttleKey) >= 5) {

                Cache::put($lockKey, time() + 60, 60);
                RateLimiter::clear($throttleKey);

                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan login. Silakan tunggu 60 detik lagi.'
                ], 429);
            }

            return response()->json([
                'success' => false,
                'field'   => 'password_login',
                'message' => 'Password yang Anda masukkan salah.'
            ], 401);
        }

        // 4. CEK PEMBATASAN AKSES jika belum di aprov oleh admin belum bisa login
        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum disetujui oleh Admin. Silakan tunggu hingga proses verifikasi selesai.'
            ], 403);
        }

        // Reset Limiter jika lolos autentikasi
        RateLimiter::clear($throttleKey);

        // 5. Pembuatan Session
        $request->session()->regenerate();

        $request->session()->put([
            'user_id'      => $user->id,
            'user_logged'  => $user->email,
            'nama_lengkap' => $user->nama_lengkap,
            'divisi'       => $user->divisi,
            'user_role'    => $user->role
        ]);
        
        $request->session()->save();

        if ($user->role === 'admin') {
            $redirectUrl = route('admin.dashboard');
        } elseif ($user->role === 'pj') {
            $redirectUrl = route('pj.dashboard');
        } else {
            $redirectUrl = route('user.dashboard');
        }

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