<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Pesanan';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        return [
            'datasets' => [
                [
                    'label' => 'Pesanan',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#9ca3af', // pending - gray
                        '#fbbf24', // processing - yellow
                        '#3b82f6', // shipped - blue
                        '#22c55e', // completed - green
                        '#ef4444', // cancelled - red
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
