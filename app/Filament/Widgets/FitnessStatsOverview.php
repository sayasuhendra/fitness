<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\ClassBooking;
use App\Models\Member;
use App\Models\MembershipPurchase;
use App\Models\Order;
use App\Models\Product;
use App\Models\Trainer;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FitnessStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Business Snapshot';

    protected ?string $description = 'Live operational metrics for Fitness Akhwat.';

    protected function getStats(): array
    {
        $membershipRevenue = (float) MembershipPurchase::query()
            ->where('status', 'active')
            ->sum('amount');
        $storeRevenue = (float) Order::query()
            ->where('status', 'completed')
            ->sum('total_price');
        $activeMembers = Member::query()
            ->whereHas('membershipPurchases', fn ($query) => $query
                ->where('status', 'active')
                ->where('expires_at', '>=', now()))
            ->count();
        $bookingsToday = ClassBooking::query()
            ->where('status', 'confirmed')
            ->whereDate('booked_at', today())
            ->count();

        return [
            Stat::make('Active Members', number_format($activeMembers))
                ->description(Member::query()->count().' total registered members')
                ->descriptionIcon(Heroicon::Identification)
                ->color('success'),
            Stat::make('Total Revenue', 'Rp '.number_format($membershipRevenue + $storeRevenue, 0, ',', '.'))
                ->description('Membership + completed store orders')
                ->descriptionIcon(Heroicon::Banknotes)
                ->color('primary'),
            Stat::make('Bookings Today', number_format($bookingsToday))
                ->description(ClassBooking::query()->where('status', 'confirmed')->count().' confirmed bookings')
                ->descriptionIcon(Heroicon::CalendarDays)
                ->color('info'),
            Stat::make('Attendance This Month', number_format(Attendance::query()->whereMonth('check_in_time', now()->month)->whereYear('check_in_time', now()->year)->count()))
                ->description('QR check-ins recorded')
                ->descriptionIcon(Heroicon::CheckCircle)
                ->color('success'),
            Stat::make('Active Trainers', number_format(Trainer::query()->where('is_active', true)->count()))
                ->description('Available for schedule assignment')
                ->descriptionIcon(Heroicon::AcademicCap)
                ->color('warning'),
            Stat::make('Low Stock Products', number_format(Product::query()->where('stock', '<=', 5)->count()))
                ->description('Products at or below 5 units')
                ->descriptionIcon(Heroicon::ExclamationTriangle)
                ->color('danger'),
        ];
    }
}
