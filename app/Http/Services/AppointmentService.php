<?php

namespace App\Http\Services;

use App\Models\Appointment;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class AppointmentService
{
    private int $appointmentDuration = 60;
    private int $appointmentGap = 30;

    public function getAvailableAppointments($consultantId, $inputDate): array
    {
        $dayOfWeek = Carbon::parse($inputDate)->dayOfWeek;

        if ($dayOfWeek === CarbonInterface::SATURDAY || $dayOfWeek === CarbonInterface::SUNDAY) {
            return [];
        }

        $consultantBookedAppointments = Appointment::where('consultant_id', $consultantId)
            ->whereDate('start_time', $inputDate)
            ->get();

        $notAvailableIntervals = [
            [
                'start_time' => $inputDate . ' 13:00:00',
                'end_time' => $inputDate . ' 15:30:00',
                'lunchBreak' => true,
            ],
        ];

        $availableAppointments = [];

        foreach ($consultantBookedAppointments as $appointment) {
            $notAvailableIntervals[] = [
                'start_time' => $appointment->start_time,
                'end_time' => $appointment->end_time,
                'lunchBreak' => false,
            ];
        }

        $startTime = Carbon::parse($inputDate . ' ' . '09:00:00');
        $endTime = Carbon::parse($inputDate . ' ' . '21:00:00');

        $currentLoopTime = $startTime;
        $i = 0;
        while ($currentLoopTime->lte($endTime)) {
            $i++;

            $bookedInfo = $this->isIntervalBookedByHour($currentLoopTime, $notAvailableIntervals, $i);
            $isBooked = $bookedInfo['isBooked'];

            if ($isBooked) {
                $lunchBreak = $bookedInfo['lunchBreak'];
                $bookedEndTime = Carbon::parse($bookedInfo['end_time']);
                if ($lunchBreak) {
                    // for lunch break we must ignore the gap
                    $currentLoopTime->addMinutes($bookedEndTime->diffInMinutes($currentLoopTime));
                } else {
                    $currentLoopTime->addMinutes($bookedEndTime->diffInMinutes($currentLoopTime) + $this->appointmentGap);
                }
            } else {
                if (!$this->canBookAppointmentAtTime($currentLoopTime, $availableAppointments)) {
                    $availableAppointments[] = [
                        'start_time' => $currentLoopTime->format('Y-m-d H:i:s'),
                        'end_time' => $currentLoopTime->clone()->addMinutes($this->appointmentDuration)->format('Y-m-d H:i:s'),
                    ];
                }
                $currentLoopTime->addMinutes($this->appointmentGap);
            }
        }

        return $availableAppointments;
    }

    private function canBookAppointmentAtTime($currentTime, $availableAppointments): bool
    {
        foreach ($availableAppointments as $appointment) {
            $endTime = Carbon::parse($appointment['start_time']);
            $endTimeWithGap = $endTime->copy()->addMinutes($this->appointmentDuration);
            if ($endTimeWithGap->gte($currentTime)) {
                return true;
            }
        }

        return false;
    }

    private function isIntervalBookedByHour($currentTime, $notAvailableIntervals, $i): array
    {
        foreach ($notAvailableIntervals as $interval) {
            $startDateTime = Carbon::parse($interval['start_time']);
            $endDateTime = Carbon::parse($interval['end_time']);
            if ($currentTime->gte($startDateTime) && $currentTime->lessThan($endDateTime)) {
                return [
                    'isBooked' => true,
                    'lunchBreak' => $interval['lunchBreak'],
                    'start_time' => $interval['start_time'],
                    'end_time' => $interval['end_time'],
                ];
            }
        }

        return [
            'isBooked' => false,
        ];
    }
}
