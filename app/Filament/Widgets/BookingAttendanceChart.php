<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\ClassBooking;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class BookingAttendanceChart extends ChartWidget
{
    use HasFiltersSchema;

    protected static ?int $sort = 100;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Bookings vs Attendance';

    protected ?string $description = 'Confirmed bookings compared with QR check-ins for the last 14 days.';

    protected string $color = 'success';

    protected ?string $maxHeight = '360px';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date_from')
                    ->label('Dari Tanggal')
                    ->default(today()->subDays(13)),
                DatePicker::make('date_to')
                    ->label('Sampai Tanggal')
                    ->default(today()),
            ]);
    }

    protected function getData(): array
    {
        [$start, $end] = $this->selectedDateRange();

        $bookingsByDate = ClassBooking::query()
            ->where('status', 'confirmed')
            ->whereDate('booked_at', '>=', $start->toDateString())
            ->whereDate('booked_at', '<=', $end->toDateString())
            ->get(['booked_at'])
            ->groupBy(fn (ClassBooking $booking): string => $booking->booked_at->toDateString())
            ->map->count();

        $attendanceByDate = Attendance::query()
            ->whereDate('check_in_time', '>=', $start->toDateString())
            ->whereDate('check_in_time', '<=', $end->toDateString())
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

    /**
     * @return array{0: CarbonImmutable, 1: CarbonImmutable}
     */
    private function selectedDateRange(): array
    {
        $start = CarbonImmutable::parse($this->filters['date_from'] ?? today()->subDays(13));
        $end = CarbonImmutable::parse($this->filters['date_to'] ?? today());

        if ($start->gt($end)) {
            return [$end, $start];
        }

        return [$start, $end];
    }
}
