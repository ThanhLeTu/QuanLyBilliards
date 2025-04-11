<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Tables routes (reference)
Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::resource('tables', TableController::class);

// Services routes (follow same pattern as tables)
Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
Route::resource('services', ServiceController::class);

// Reservations routes
Route::get('/reservations/data', [ReservationController::class, 'data'])->name('reservations.data');
Route::resource('reservations', ReservationController::class);
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::patch('/reservations/cancel/{table_id}', [ReservationController::class, 'cancel'])
     ->name('reservations.cancel');
Route::patch('/reservations/confirm/{id}', [ReservationController::class, 'confirmReservation']);

Route::get('/table-stats', [HomeController::class, 'getTableStats'])->name('table.stats');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/password/reset', [AuthController::class, 'showResetForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');      