<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class IVETodayPie extends ChartWidget
{
    protected static ?int $sort = 6;
    protected static ?string $heading = 'Income Vs Expense Today';
    protected static ?string $maxHeight = '250px';

    protected static ?array $options = [
        'scales' => [
            'y' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ]
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ]
            ],
        ],
    ];

    protected function getData(): array
    {
        $income = Order::whereDate('created_at', today())->whereIn('status', ['ready', 'delivered'])->sum('total');
        $expense = Expense::whereDate('created_at', today())->sum('amount');
        return [
            'labels' => [
                'Income',
                'Expense'
            ],
            'datasets' => [
                [
                    'label' => 'Total: ',
                    'data' => [$income, $expense],
                    'backgroundColor' => [
                        '#8BC34A',
                        '#C62828'
                    ],
                    'borderColor' => '#333'
                ],
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
