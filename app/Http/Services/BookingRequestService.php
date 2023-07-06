<?php

namespace App\Http\Services;

use App\Models\Appointment;
use Illuminate\Http\Request;

class BookingRequestService
{
    private AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function validateAndBookAppointment(Request $request)
    {
        $request->validate([
            'consultant' => 'required',
            'date' => 'required|date',
            'time_slot' => 'required',
            'name' => 'required',
        ]);

        $bookingInterval = json_decode($request->input('time_slot'), true);
        $consultantId = $request->input('consultant');
        $inputDate = $request->input('date');

        if (!$this->isBookingIntervalAvailable($bookingInterval, $consultantId, $inputDate)) {
            return response()->json(['error' => 'Selected booking interval is not available. Please choose another.'], 400);
        }

        return $this->saveAppointment($request, $bookingInterval);
    }

    private function isBookingIntervalAvailable($bookingInterval, $consultantId, $inputDate): bool
    {
        $availableIntervals = $this->appointmentService->getAvailableAppointments($consultantId, $inputDate);

        foreach ($availableIntervals as $interval) {
            if ($bookingInterval['start_date'] == $interval['start_time'] && $bookingInterval['end_date'] == $interval['end_time']) {
                return true;
            }
        }

        return false;
    }

    private function saveAppointment(Request $request, $bookingInterval): Appointment
    {
        $appointment = new Appointment();
        $appointment->consultant_id = $request->input('consultant');
        $appointment->start_time = $bookingInterval['start_date'];
        $appointment->end_time = $bookingInterval['end_date'];
        $appointment->name = $request->input('name');
        $appointment->details = $request->input('details');
        $appointment->save();

        return $appointment;
    }

}
