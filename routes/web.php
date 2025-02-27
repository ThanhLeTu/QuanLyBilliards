<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\TableController;

Route::get('/tables/data', [TableController::class, 'data'])->name('tables.data');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('tables', TableController::class);