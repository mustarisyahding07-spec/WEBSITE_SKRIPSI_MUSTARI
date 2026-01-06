<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;

class ProductionVsSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Production vs Sales (Monthly Target)';

    protected function getData(): array
    {
        // Simple example: Sales by month for current year
        // In real app, you'd loop months 1-12
        $salesData = [];
        $targetData = [];
        $labels = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 1));
            $targetData[] = 50; // 50kg target constant
            
            // Sum weight for this month
            $sales = Order::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $i)
                ->where('status', 'completed')
                ->sum('total_weight');
            
            $salesData[] = $sales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Production Target (kg)',
                    'data' => $targetData,
                    'borderColor' => '#FF6384',
                    'borderDash' => [5, 5],
                ],
                [
                    'label' => 'Actual Sales (kg)',
                    'data' => $salesData,
                    'borderColor' => '#36A2EB',
                    'fill' => true,
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
