<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Bulanan';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Order::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $formattedData = [];
        $labels = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $data->firstWhere('month', $i);
            $formattedData[] = $monthData ? $monthData->total : 0;
            $labels[] = date('F', mktime(0, 0, 0, $i, 1));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $formattedData,
                    'borderColor' => '#fbbf24', 
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(251, 191, 36, 0.1)',
                    'tension' => 0.4,
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
