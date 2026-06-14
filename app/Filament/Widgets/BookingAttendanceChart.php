<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\ClassBooking;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class BookingAttendanceChart extends ChartWidget
{
    protected ?string $heading = 'Bookings vs Attendance';

    protected ?string $description = 'Confirmed bookings compared with QR check-ins for the last 14 days.';

    protected string $color = 'success';

    protected function getData(): array
    {
        $start = CarbonImmutable::today()->subDays(13);
        $end = CarbonImmutable::today()->endOfDay();

        $bookingsByDate = ClassBooking::query()
            ->where('status', 'confirmed')
            ->whereBetween('booked_at', [$start, $end])
            ->get(['booked_at'])
            ->groupBy(fn (ClassBooking $booking): string => $booking->booked_at->toDateString())
            ->map->count();

        $attendanceByDate = Attendance::query()
            ->whereBetween('check_in_time', [$start, $end])
            ->get(['check_in_time'])
            ->groupBy(fn (Attendance $attendance): string => $attendance->check_in_time->toDateString())
            ->map->count();

        $labels = [];
        $bookings = [];
        $attendance = [];

        for ($date = $start; $date->lte($end); $date = $date->addDay()) {
            $key = $date->toDateString();
            $labels[] = $date->format('d M');
            $bookings[] = $bookingsByDate->get($key, 0);
            $attendance[] = $attendanceByDate->get($key, 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $bookings,
                ],
                [
                    'label' => 'Attendance',
                    'data' => $attendance,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
