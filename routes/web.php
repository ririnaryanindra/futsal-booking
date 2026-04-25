<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\FieldController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'nocache'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('fields', FieldController::class);
    Route::resource('bookings', BookingController::class);
    

Route::resource('members', MemberController::class);
});

require __DIR__ . '/auth.php';
