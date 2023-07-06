<?php

namespace App\Http\Controllers;

use App\Http\Services\AppointmentService;
use Illuminate\Support\Carbon;

class AppointmentsController extends Controller
{
    private AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function getAvailableBookingIntervals($consultantId, $inputDate): \Illuminate\Http\JsonResponse
    {
        $availableAppointments = $this->appointmentService->getAvailableAppointments($consultantId, $inputDate);

        $formattedAppointments = array_map(function ($appointment) {
            return [
                'start_time' => Carbon::parse($appointment['start_time'])->format('H:i'),
                'end_time' => Carbon::parse($appointment['end_time'])->format('H:i'),
                'start_date' => Carbon::parse($appointment['start_time'])->format('Y-m-d H:i:s'),
                'end_date' => Carbon::parse($appointment['end_time'])->format('Y-m-d H:i:s'),
            ];
        }, $availableAppointments);

        return response()->json($formattedAppointments);
    }
}


