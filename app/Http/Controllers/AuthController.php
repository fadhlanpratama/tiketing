<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
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
                'nama_lengkap' => [
                    'required', 'string', 'min:3', 'max:150',
                    Rule::when($request->input('role') === 'pj', [
                        Rule::unique('users', 'nama_lengkap')
                            ->where(fn ($query) => $query->where('role', 'pj')),
                    ]),
                ],
                'email'        => 'required|email:rfc,dns|max:254|unique:users,email',
                'divisi'       => 'required|string|in:IT,Humas,Perpustakaan,Perencanaan,Keuangan,Monitoring,Kepegawaian,Sarana Prasarana,Keamanan dan Kebersihan,Pengadaan,Kearsipan,Angkutan',
                'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
                'role'         => 'required|in:user,pj',
                'password'     => [
                    'required', 
                    'string', 
                    'confirmed', 
                    Password::min(8)
                        ->letters()
                        ->numbers()
                        ->mixedCase()
                ],
            ], [
                'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
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
            'role'         => $request->role,         
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
            ], [
                'Email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        }

        $user = Users::where('email', $request->Email)->first();

        if ($user) {
            $throttleKey = \Illuminate\Support\Str::lower($request->Email) . '|' . $request->ip();

            // 1. Cek apakah user saat ini sedang dalam kondisi terblokir (klik ke-6++ dst)
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
                
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan login. Silakan tunggu " . $seconds . " detik lagi."
                ], 429);
            }

            // 2. Cek apakah password salah
            if (!Hash::check($request->password, $user->password)) {
                \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);

                // FITUR UTAMA: Jika sentuhan klik ini adalah kegagalan ke-5, paksa kunci 60 detik penuh
                if (\Illuminate\Support\Facades\RateLimiter::attempts($throttleKey) === 5) {
                    $cacheKey = config('cache.prefix') . ':timer:' . $throttleKey;
                    \Illuminate\Support\Facades\Cache::put($cacheKey, now()->addSeconds(60)->timestamp, 60);

                    // Berikan respon 429 instan di klik ke-5 dengan waktu tepat 60 detik
                    return response()->json([
                        'success' => false,
                        'message' => "Terlalu banyak percobaan login. Silakan tunggu 60 detik lagi."
                    ], 429);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah.'
                ], 401);
            }

            // Jika password benar, bersihkan catatan kegagalan email ini
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

        } else {
            // JIKA EMAIL TIDAK TERDAFTAR DI DATABASE
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // --- PROSES PEMBUATAN SESSION JIKA LOGIN SUKSES ---
        $request->session()->regenerate();

        $request->session()->put([
            'user_id'      => $user->id,
            'user_logged'  => $user->email,
            'nama_lengkap' => $user->nama_lengkap,
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