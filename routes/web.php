<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::resource('tables', TableController::class);
Route::resource('services', ServiceController::class);

// Routes for Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
