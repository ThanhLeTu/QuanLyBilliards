<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ServiceController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// Tables routes (reference)
Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::resource('tables', TableController::class);

// Services routes (follow same pattern as tables)
Route::get('/services/data', [ServiceController::class, 'data'])->name('services.data');
Route::resource('services', ServiceController::class);