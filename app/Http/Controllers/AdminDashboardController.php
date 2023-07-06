<?php

namespace App\Http\Controllers;

use App\Http\Services\BookingRequestService;
use App\Models\Appointment;
use App\Models\Consultant;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    private BookingRequestService $bookingService;

    public function __construct(BookingRequestService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function index(Request $request): View|Application|Factory|ApplicationContract
    {
        $filter = $request->input('consultant-filter');

        $query = Appointment::select(
                'appointments.id as id',
                'appointments.start_time',
                'appointments.end_time',
                'appointments.name',
                'consultants.id as consultant_id',
                'consultants.first_name',
                'consultants.last_name'
            )->join('consultants', 'appointments.consultant_id', '=', 'consultants.id')
            ->orderBy('appointments.start_time')
            ->orderBy('consultants.first_name')
            ->orderBy('consultants.last_name');

        if ($filter) {
            $query->where('appointments.consultant_id', $filter);
        }

        $consultants = Consultant::all();
        $appointments = $query->paginate(10);

        return view('admin.dashboard', compact('appointments', 'consultants', 'filter'));
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        $request->session()->flash('success', 'Appointment successfully deleted.');

        return redirect()->route('admin.dashboard');
    }

    public function create(): View|Application|Factory|ApplicationContract
    {
        $consultants = Consultant::all();
        return view('admin.add-booking', compact('consultants'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $appointment = $this->bookingService->validateAndBookAppointment($request);

        if ($appointment instanceof JsonResponse) {
            return $appointment;
        }

        $this->flashSuccessMessage($request, 'Appointment successfully booked!');
        return redirect()->route('admin.dashboard');
    }

    private function flashSuccessMessage($request, string $message)
    {
        $request->session()->flash('success', $message);
    }

}
