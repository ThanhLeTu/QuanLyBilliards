<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// Tables routes (reference)
Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::resource('tables', TableController::class);
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/service', [BookingController::class, 'index'])->name('bookings.index');
// Services routes (follow same pattern as tables)
Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
Route::resource('services', ServiceController::class);