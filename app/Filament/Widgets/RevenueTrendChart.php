<?php

namespace App\Filament\Widgets;

use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Support\AdminShift;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class RevenueTrendChart extends ChartWidget
{
    use HasFiltersSchema;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Tren Pendapatan';

    protected ?string $description = 'Omzet paket, penjualan produk, dan profit produk berdasarkan tanggal transaksi admin lokasi.';

    protected string $color = 'primary';

    protected ?string $maxHeight = '360px';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('shift')
                    ->label('Shift')
                    ->options([
                        'all' => 'Semua Shift',
                        AdminShift::SHIFT_1 => AdminShift::label(AdminShift::SHIFT_1),
                        AdminShift::SHIFT_2 => AdminShift::label(AdminShift::SHIFT_2),
                    ])
                    ->default('all'),
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
        $shift = $this->selectedShift();

        $membershipRevenue = MembershipPurchase::query()
            ->where('status', 'active')
            ->whereNotNull('handled_by')
            ->whereDate('handled_date', '>=', $start->toDateString())
            ->whereDate('handled_date', '<=', $end->toDateString())
            ->when($shift !== 'all', fn ($query) => $query->where('handled_shift', $shift))
            ->get(['amount', 'handled_date'])
            ->groupBy(fn (MembershipPurchase $purchase): string => $purchase->handled_date->toDateString())
            ->map(fn ($rows): float => (float) $rows->sum('amount'));

        $storeRows = Order::query()
            ->with('items')
            ->whereIn('status', ['paid', 'completed'])
            ->whereNotNull('handled_by')
            ->whereDate('handled_date', '>=', $start->toDateString())
            ->whereDate('handled_date', '<=', $end->toDateString())
            ->when($shift !== 'all', fn ($query) => $query->where('handled_shift', $shift))
            ->get(['id', 'total_price', 'handled_date']);

        $storeRevenue = $storeRows
            ->groupBy(fn (Order $order): string => $order->handled_date->toDateString())
            ->map(fn ($rows): float => (float) $rows->sum('total_price'));

        $storeProfit = $storeRows
            ->groupBy(fn (Order $order): string => $order->handled_date->toDateString())
            ->map(fn ($rows): float => (float) $rows->sum(fn (Order $order): float => (float) $order->items->sum('profit_amount')));

        $labels = [];
        $membership = [];
        $store = [];
        $profit = [];
        $total = [];

        for ($date = $start; $date->lte($end); $date = $date->addDay()) {
            $key = $date->toDateString();
            $labels[] = $date->format('d M');
            $membershipValue = $membershipRevenue->get($key, 0);
            $storeValue = $storeRevenue->get($key, 0);
            $profitValue = $storeProfit->get($key, 0);

            $membership[] = $membershipValue;
            $store[] = $storeValue;
            $profit[] = $profitValue;
            $total[] = $membershipValue + $storeValue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Omzet',
                    'data' => $total,
                    'borderWidth' => 3,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Paket',
                    'data' => $membership,
                    'borderWidth' => 2,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Produk',
                    'data' => $store,
                    'borderWidth' => 2,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Profit Produk',
                    'data' => $profit,
                    'borderDash' => [6, 4],
                    'borderWidth' => 2,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array|RawJs|null
    {
        return RawJs::make(<<<'JS'
            {
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
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
        return 'line';
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

    private function selectedShift(): string
    {
        $shift = $this->filters['shift'] ?? 'all';

        return in_array($shift, ['all', AdminShift::SHIFT_1, AdminShift::SHIFT_2], true) ? $shift : 'all';
    }
}
