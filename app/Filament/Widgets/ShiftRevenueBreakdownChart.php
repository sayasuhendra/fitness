<?php

namespace App\Filament\Widgets;

use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Support\AdminShift;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class ShiftRevenueBreakdownChart extends ChartWidget
{
    use HasFiltersSchema;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Pendapatan per Shift';

    protected ?string $description = 'Perbandingan omzet paket, omzet produk, dan profit produk per shift pada periode terpilih.';

    protected string $color = 'success';

    protected ?string $maxHeight = '320px';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date_from')
                    ->label('Dari Tanggal')
                    ->default(today()->startOfMonth()),
                DatePicker::make('date_to')
                    ->label('Sampai Tanggal')
                    ->default(today()),
            ]);
    }

    protected function getData(): array
    {
        [$start, $end] = $this->selectedDateRange();
        $shifts = [AdminShift::SHIFT_1, AdminShift::SHIFT_2];

        $membershipRevenue = MembershipPurchase::query()
            ->where('status', 'active')
            ->whereNotNull('handled_by')
            ->whereDate('handled_date', '>=', $start->toDateString())
            ->whereDate('handled_date', '<=', $end->toDateString())
            ->whereIn('handled_shift', $shifts)
            ->get(['amount', 'handled_shift'])
            ->groupBy('handled_shift')
            ->map(fn ($rows): float => (float) $rows->sum('amount'));

        $orders = Order::query()
            ->with('items')
            ->whereIn('status', ['paid', 'completed'])
            ->whereNotNull('handled_by')
            ->whereDate('handled_date', '>=', $start->toDateString())
            ->whereDate('handled_date', '<=', $end->toDateString())
            ->whereIn('handled_shift', $shifts)
            ->get(['id', 'total_price', 'handled_shift']);

        $storeRevenue = $orders
            ->groupBy('handled_shift')
            ->map(fn ($rows): float => (float) $rows->sum('total_price'));

        $storeProfit = $orders
            ->groupBy('handled_shift')
            ->map(fn ($rows): float => (float) $rows->sum(fn (Order $order): float => (float) $order->items->sum('profit_amount')));

        return [
            'datasets' => [
                [
                    'label' => 'Paket',
                    'data' => array_map(fn (string $shift): float => $membershipRevenue->get($shift, 0), $shifts),
                ],
                [
                    'label' => 'Produk',
                    'data' => array_map(fn (string $shift): float => $storeRevenue->get($shift, 0), $shifts),
                ],
                [
                    'label' => 'Profit Produk',
                    'data' => array_map(fn (string $shift): float => $storeProfit->get($shift, 0), $shifts),
                ],
            ],
            'labels' => array_map(fn (string $shift): string => AdminShift::label($shift), $shifts),
        ];
    }

    protected function getOptions(): array|RawJs|null
    {
        return RawJs::make(<<<'JS'
            {
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => `${context.dataset.label}: Rp ${Number(context.parsed.y ?? 0).toLocaleString('id-ID')}`,
                        },
                    },
                },
                scales: {
                    x: {
                        stacked: false,
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => `Rp ${Number(value).toLocaleString('id-ID')}`,
                        },
                    },
                },
            }
        JS);
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
        $start = CarbonImmutable::parse($this->filters['date_from'] ?? today()->startOfMonth());
        $end = CarbonImmutable::parse($this->filters['date_to'] ?? today());

        if ($start->gt($end)) {
            return [$end, $start];
        }

        return [$start, $end];
    }
}
