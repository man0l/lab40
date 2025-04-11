<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::controller(BookingsController::class)->group(function () {
    Route::get('/bookings', 'index');
    Route::get('/bookings/create', 'create');
    Route::get('/bookings/edit/{id}', 'edit');
    Route::get('/bookings/{id}', 'show');

    Route::post('/bookings', 'store');
    Route::put('/bookings/{id}', 'update');
    Route::delete('/bookings/{id}', 'destroy');    
});