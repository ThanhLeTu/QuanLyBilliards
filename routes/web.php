<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationServiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EmployeeController;

Route::post('/invoices', [InvoiceController::class, 'store']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);


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
Route::patch('/reservations/confirm/{id}', [ReservationController::class, 'confirmReservation']);

Route::get('/table-stats', [HomeController::class, 'getTableStats'])->name('table.stats');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
    Route::resource('tables', TableController::class);
    Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
    Route::resource('services', ServiceController::class);
    Route::resource('reservations', ReservationController::class);
    Route::patch('/reservations/cancel/{table_id}', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::patch('/reservations/confirm/{id}', [ReservationController::class, 'confirmReservation']);
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');
    Route::get('/reservations/customer-by-table/{table_id}', [ReservationController::class, 'getCustomerByTableId']);
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
});

Route::get('/reservations/playing-info/{table_id}', [ReservationController::class, 'getPlayingInfo']);
Route::get('/reservations/by-table/{tableId}', [ReservationController::class, 'getByTable']);
// routes/web.php hoặc routes/api.php
Route::post('/update-reservation-service', [ReservationServiceController::class, 'update'])->name('update-reservation-service');
Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::post('/payment/momo', [PaymentController::class, 'createMomoPayment'])->name('payment.momo');
Route::get('/payment/momo-return', [PaymentController::class, 'momoReturn'])->name('payment.momoReturn');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::resource('employees', EmployeeController::class);