<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class CustomerGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Pelanggan Baru';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = User::selectRaw('MONTH(created_at) as month, count(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $formattedData = [];
        $labels = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $data->firstWhere('month', $i);
            $formattedData[] = $monthData ? $monthData->count : 0;
            $labels[] = date('F', mktime(0, 0, 0, $i, 1));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pelanggan Baru',
                    'data' => $formattedData,
                    'borderColor' => '#0ea5e9', // Sky blue
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
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
