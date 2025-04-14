<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('booking.index');
});
Route::resource('booking', BookingController::class);