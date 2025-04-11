<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);
Route::resource('booking', BookingController::class);