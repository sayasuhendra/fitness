<?php

namespace App\Filament\Widgets;

use App\Models\MembershipPurchase;
use App\Models\Order;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;

class RevenueTrendChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Trend';

    protected ?string $description = 'Daily membership and store revenue over the last 14 days.';

    protected string $color = 'primary';

    protected function getData(): array
    {
        $start = CarbonImmutable::today()->subDays(13);
        $end = CarbonImmutable::today()->endOfDay();

        $membershipRevenue = MembershipPurchase::query()
            ->where('status', 'active')
            ->whereBetween('created_at', [$start, $end])
            ->get(['amount', 'created_at'])
            ->groupBy(fn (MembershipPurchase $purchase): string => $purchase->created_at->toDateString())
            ->map(fn ($rows): float => (float) $rows->sum('amount'));

        $storeRevenue = Order::query()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->get(['total_price', 'created_at'])
            ->groupBy(fn (Order $order): string => $order->created_at->toDateString())
            ->map(fn ($rows): float => (float) $rows->sum('total_price'));

        $labels = [];
        $membership = [];
        $store = [];

        for ($date = $start; $date->lte($end); $date = $date->addDay()) {
            $key = $date->toDateString();
            $labels[] = $date->format('d M');
            $membership[] = $membershipRevenue->get($key, 0);
            $store[] = $storeRevenue->get($key, 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Membership',
                    'data' => $membership,
                ],
                [
                    'label' => 'Store',
                    'data' => $store,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
