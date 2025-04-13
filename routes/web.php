<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EmployeeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Tables
Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::resource('tables', TableController::class);

// Services
Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
Route::resource('services', ServiceController::class);
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

// Reservations
Route::get('/reservations/data', [ReservationController::class, 'data'])->name('reservations.data');
Route::resource('reservations', ReservationController::class);
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::patch('/reservations/cancel/{table_id}', [ReservationController::class, 'cancel'])->name('reservations.cancel');
Route::patch('/reservations/confirm/{id}', [ReservationController::class, 'confirmReservation']);

// Thống kê bàn
Route::get('/table-stats', [HomeController::class, 'getTableStats'])->name('table.stats');

// Auth
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Middleware Auth
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

    Route::get('/reservations/customer-by-table/{table_id}', [ReservationController::class, 'getCustomerByTableId']);
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
});

// Các route liên quan đến trạng thái chơi
Route::get('/reservations/playing-info/{table_id}', [ReservationController::class, 'getPlayingInfo']);
Route::get('/reservations/by-table/{tableId}', [ReservationController::class, 'getByTable']);

// Thanh toán Momo
Route::post('/payment/momo', [PaymentController::class, 'payWithMomo'])->name('payment.momo');
Route::get('/payment/momo-callback', [PaymentController::class, 'handleMomoCallback'])->name('payment.momo.callback');

// Nhân viên
Route::resource('employees', EmployeeController::class);

// (Nếu cần Booking sau này)
// Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
// Route::post('/bookings/add-service', [BookingController::class, 'addService'])->name('bookings.add-service');
// Route::post('/bookings/{id}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
// Route::get('/bookings/{id}/cart', [BookingController::class, 'getCart'])->name('bookings.cart');
