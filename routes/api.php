<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Services\BookingRequestService;
use App\Models\Consultant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// basic api, not used with the laravel app

Route::get('/consultants', function (Request $request) {
    return Consultant::all();
});

Route::get('/appointments/{consultantId}/{date}', [AppointmentsController::class, 'getAvailableBookingIntervals']);

Route::post('/book', function (Request $request, BookingRequestService $bookingService) {
    $appointment = $bookingService->validateAndBookAppointment($request);
    if ($appointment instanceof JsonResponse) {
        return $appointment;
    }

    return response()->json(['message' => 'Appointment booked successfully']);
});
