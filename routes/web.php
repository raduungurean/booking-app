<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'index'])
    ->name('bookings.index');
Route::post('/book', [BookingController::class, 'store'])
    ->name('bookings.store');

Route::get('/appointments/{consultantId}/{date}', [AppointmentsController::class, 'getAvailableBookingIntervals']);

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'postLogin']);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('admin.logout')
        ->middleware('auth');

    Route::get('/', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('auth');

    Route::delete('/appointments/{id}', [AdminDashboardController::class, 'destroy'])
        ->name('admin.appointments.destroy')
        ->middleware('auth');

    Route::get('/add-booking', [AdminDashboardController::class, 'create'])
        ->name('admin.add-booking')
        ->middleware('auth');

    Route::post('/add-booking', [AdminDashboardController::class, 'store'])
        ->name('admin.store-booking')
        ->middleware('auth');
});
