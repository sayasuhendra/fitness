<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Support\AdminShift;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use UnitEnum;

class ShiftRevenueReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBarSquare;

    protected static ?string $navigationLabel = 'Laporan Shift';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.shift-revenue-report';

    public string $dateFrom;

    public string $dateTo;

    public function mount(): void
    {
        $this->dateFrom = today()->startOfMonth()->toDateString();
        $this->dateTo = today()->toDateString();
    }

    public function getTitle(): string|Htmlable
    {
        return 'Laporan Pendapatan Shift';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['Owner', 'Super admin']) ?? false;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function rows(): Collection
    {
        $memberships = MembershipPurchase::query()
            ->with('handler')
            ->whereNotNull('handled_by')
            ->whereBetween('handled_date', [$this->dateFrom, $this->dateTo])
            ->whereIn('status', ['active'])
            ->get()
            ->map(fn (MembershipPurchase $purchase): array => [
                'date' => $purchase->handled_date?->toDateString(),
                'shift' => $purchase->handled_shift,
                'admin' => $purchase->handler?->name ?? '-',
                'membership_revenue' => (float) $purchase->amount,
                'store_revenue' => 0.0,
                'store_profit' => 0.0,
            ]);

        $orders = Order::query()
            ->with('handler', 'items')
            ->whereNotNull('handled_by')
            ->whereBetween('handled_date', [$this->dateFrom, $this->dateTo])
            ->whereIn('status', ['paid', 'completed'])
            ->get()
            ->map(fn (Order $order): array => [
                'date' => $order->handled_date?->toDateString(),
                'shift' => $order->handled_shift,
                'admin' => $order->handler?->name ?? '-',
                'membership_revenue' => 0.0,
                'store_revenue' => (float) $order->total_price,
                'store_profit' => (float) $order->items->sum('profit_amount'),
            ]);

        return $memberships
            ->concat($orders)
            ->groupBy(fn (array $row): string => $row['date'].'|'.$row['shift'].'|'.$row['admin'])
            ->map(function (Collection $rows): array {
                $first = $rows->first();
                $membershipRevenue = (float) $rows->sum('membership_revenue');
                $storeRevenue = (float) $rows->sum('store_revenue');
                $storeProfit = (float) $rows->sum('store_profit');

                return [
                    'date' => $first['date'],
                    'shift' => $first['shift'],
                    'shift_label' => AdminShift::label($first['shift']),
                    'admin' => $first['admin'],
                    'membership_revenue' => $membershipRevenue,
                    'store_revenue' => $storeRevenue,
                    'store_profit' => $storeProfit,
                    'total_revenue' => $membershipRevenue + $storeRevenue,
                ];
            })
            ->sortByDesc('date')
            ->values();
    }

    public function money(float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }
}
