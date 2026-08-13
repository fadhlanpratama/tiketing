<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PjController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\AdminAnalyticsController;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\TicketManageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// ================= AUTENTIKASI UTAMA =================
Route::middleware(['guest.redirect', 'no.cache'])->group(function () {
    Route::get('/', [AuthController::class, 'landing'])->name('landing');
    Route::get('/portal', [AuthController::class, 'showAuthForm'])->name('home');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:30,1')->name('login');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ================= AREA: USER =================
Route::prefix('user')->name('user.')->middleware(['cek.login:user', 'no.cache'])->group(function () {
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');
    Route::get('/profile/edit', [TicketController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [TicketController::class, 'updateProfile'])->name('profile.update');

    // Aksi user Terhadap tiket
    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create'); 
    Route::post('/ticket/store', [TicketController::class, 'store'])->middleware('throttle:10,1')->name('ticket.store'); 
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
    Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->name('ticket.destroy'); 
    Route::post('/ticket/{id}/chat', [TicketController::class, 'storeMessage'])->name('ticket.chat');
    Route::post('/ticket/{id}/survei', [TicketController::class, 'storeSurvey'])->name('ticket.survei');
});


// ================= AREA: PENANGGUNG JAWAB =================
Route::prefix('pj')->name('pj.')->middleware(['cek.login:pj', 'no.cache'])->group(function () {
    Route::get('/dashboard', [PjController::class, 'index'])->name('dashboard');
    Route::get('/profile/edit', [PjController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [PjController::class, 'updateProfile'])->name('profile.update');

    // Aksi PJ terhadap tiket
    Route::post('/ticket/{id}/terima', [PjController::class, 'terima'])->name('ticket.terima');
    Route::post('/ticket/{id}/selesaikan', [PjController::class, 'selesaikan'])->name('ticket.selesaikan');
    Route::get('/ticket/{id}', [PjController::class, 'show'])->name('ticket.show');
    Route::post('/ticket/{id}/chat', [PjController::class, 'storeMessage'])->name('ticket.chat');
});


// ================= AREA: ADMIN =================
Route::middleware('cek.login:admin')->prefix('admin')->name('admin.')->group(function () {

    // Halaman utama = Analitik/Dashboard
    Route::get('/', [AdminAnalyticsController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserManageController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [UserManageController::class, 'approve'])->name('user.approve');
    Route::post('/users/{id}/reject', [UserManageController::class, 'reject'])->name('user.reject');

    Route::get('/users/manage', [UserManageController::class, 'manage'])->name('users.manage');
    Route::get('/users/create', [UserManageController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManageController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserManageController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManageController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManageController::class, 'destroy'])->name('users.destroy');

    Route::get('/tickets', [TicketManageController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/all', [TicketManageController::class, 'all'])->name('tickets.all');
    Route::get('/tickets/{id}', [TicketManageController::class, 'show'])->name('ticket.show');
    Route::post('/tickets/{id}/assign', [TicketManageController::class, 'assignPJ'])->name('ticket.assign');
    Route::post('/tickets/{id}/close', [TicketManageController::class, 'close'])->name('ticket.close');
});
