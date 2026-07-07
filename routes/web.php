<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= AUTENTIKASI UTAMA =================
Route::get('/', [AuthController::class, 'showAuthForm'])->name('home');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ================= AREA: USER =================
Route::prefix('user')->name('user.')->middleware('cek.login')->group(function () { 
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');

    //tiket
    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create'); 
    Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store'); 
    Route::get('/ticket/{id}/edit', [TicketController::class, 'edit'])->name('ticket.edit'); 
    Route::put('/ticket/{id}/update', [TicketController::class, 'update'])->name('ticket.update'); 
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->name('ticket.destroy'); 
});


// ================= AREA: ADMIN =================
Route::prefix('admin')->name('admin.')->middleware('cek.login:admin')->group(function () {   
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});


// ================= UTILITAS / DEBUGGING =================
if (config('app.env') === 'local') {
    Route::get('/cek-session', function () {
        return session()->all();
    });
}