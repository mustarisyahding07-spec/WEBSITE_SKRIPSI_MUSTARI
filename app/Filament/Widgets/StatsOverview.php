<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingReviews = \App\Models\Review::where('is_approved', false)->count();

        return [
            Stat::make('Pending Orders', $pendingOrders)
                ->description('Pesanan Menunggu Pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Revenue (Monthly)', 'Rp ' . number_format(Order::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('total_amount'), 0, ',', '.'))
                ->description('Realisasi Bulan Ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Pending Reviews', $pendingReviews)
                ->description('Ulasan Menunggu Moderasi')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($pendingReviews > 0 ? 'danger' : 'success'),
                
            Stat::make('Success Rate', $successRate . '%')
                ->description("{$completedOrders} / {$totalOrders} Orders Completed")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($successRate > 80 ? 'success' : 'danger'),
        ];
    }
}
