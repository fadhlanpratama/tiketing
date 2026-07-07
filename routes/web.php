<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

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
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');
    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create'); 
    Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store'); 
    Route::get('/ticket/{id}/edit', [TicketController::class, 'edit'])->name('ticket.edit'); 
    Route::put('/ticket/{id}/update', [TicketController::class, 'update'])->name('ticket.update'); 
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->name('ticket.destroy'); 
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
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
