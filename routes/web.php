<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ================= AUTENTIKASI UTAMA =================
Route::get('/', [AuthController::class, 'showAuthForm'])->name('home');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= AREA: USER =================
Route::prefix('user')->name('user.')->group(function () { 
    Route::get('/dashboard', function () {
        if (!session()->has('user_logged') || session('user_role') !== 'user') {
            return redirect()->route('home');
        }
        return view('user.dashboard');
    })->name('dashboard');
});

// ================= AREA: ADMIN =================
Route::prefix('admin')->name('admin.')->group(function () {   
    Route::get('/dashboard', function () {
        if (!session()->has('user_logged') || session('user_role') !== 'admin') {
            return redirect()->route('home');
        }
        return view('admin.dashboard');
    })->name('dashboard');
});

// ================= UTILITAS / DEBUGGING =================
// Hanya aktif jika aplikasi berjalan di environment lokal (development)
if (config('app.env') === 'local') {
    Route::get('/cek-session', function () {
        return session()->all();
    });
}