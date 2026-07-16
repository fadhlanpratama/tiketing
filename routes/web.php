<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PjController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// ================= AUTENTIKASI UTAMA =================
Route::middleware(['guest.redirect', 'no.cache'])->group(function () {
    Route::get('/', [AuthController::class, 'showAuthForm'])->name('home');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')->name('register');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ================= AREA: USER =================
Route::prefix('user')->name('user.')->middleware(['cek.login:user', 'no.cache'])->group(function () {
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');

    //tiket
    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create'); 
    Route::post('/ticket/store', [TicketController::class, 'store'])->middleware('throttle:10,1')->name('ticket.store'); 
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->name('ticket.destroy'); 
});


// ================= AREA: PENANGGUNG JAWAB =================
Route::prefix('pj')->name('pj.')->middleware(['cek.login:pj', 'no.cache'])->group(function () {
    Route::get('/dashboard', [PjController::class, 'index'])->name('dashboard');

    // Aksi PJ terhadap tiket yang ditugaskan
    Route::post('/ticket/{id}/terima', [PjController::class, 'terima'])->name('ticket.terima');
    Route::post('/ticket/{id}/selesaikan', [PjController::class, 'selesaikan'])->name('ticket.selesaikan');
    Route::get('/ticket/{id}', [PjController::class, 'show'])->name('ticket.show');
});


// ================= AREA: ADMIN =================
Route::prefix('admin')->name('admin.')->middleware(['cek.login:admin', 'no.cache'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});
