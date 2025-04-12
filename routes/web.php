<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EmployeeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Tables routes (reference)
Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::resource('tables', TableController::class);

// Services routes (follow same pattern as tables)
Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
Route::resource('services', ServiceController::class);
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

// Reservations routes
Route::get('/reservations/data', [ReservationController::class, 'data'])->name('reservations.data');
Route::resource('reservations', ReservationController::class);
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::patch('/reservations/cancel/{table_id}', [ReservationController::class, 'cancel'])
     ->name('reservations.cancel');

Route::get('/table-stats', [HomeController::class, 'getTableStats'])->name('table.stats');
Route::patch('/reservations/confirm/{id}', [ReservationController::class, 'confirmReservation']);

// Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
// Route::post('/bookings/add-service', [BookingController::class, 'addService'])->name('bookings.add-service');
// Route::post('/bookings/{id}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
// Route::get('/bookings/{id}/cart', [BookingController::class, 'getCart'])->name('bookings.cart');

Route::resource('employees', EmployeeController::class);