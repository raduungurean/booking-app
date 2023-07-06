<?php

namespace App\Http\Controllers;

use App\Http\Services\BookingRequestService;
use App\Models\Consultant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private BookingRequestService $bookingService;

    public function __construct(BookingRequestService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $consultants = Consultant::all();
        return view('front.home', compact('consultants'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $appointment = $this->bookingService->validateAndBookAppointment($request);

        if ($appointment instanceof JsonResponse) {
            return $appointment;
        }

        $this->flashSuccessMessage($request, 'Appointment successfully booked!');

        return redirect()->route('bookings.index');
    }

    private function flashSuccessMessage($request, string $message)
    {
        $request->session()->flash('success', $message);
    }
}
