<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Expense;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class IncomeVsExpense extends ChartWidget
{
    protected static ?int $sort = 5;
    protected static ?string $heading = 'Income vs Expense (Per Day)';

    protected function getData(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $incomeData = Order::whereBetween('created_at', [$currentMonth, now()->endOfYear()])
                    ->whereIn('status', ['ready', 'delivered'])
                    ->selectRaw('DATE_FORMAT(created_at, "%M %d") as date, sum(total) as total')
                    ->groupBy('date')
                    ->get()
                    ->pluck('total', 'date')
                    ->toArray();
        $expenseData = Expense::whereBetween('created_at', [$currentMonth, Carbon::now()->endOfYear()])
            ->selectRaw('DATE_FORMAT(created_at, "%M %d") as date, sum(amount) as amount')
            ->groupBy('date')
            ->get()
            ->pluck('amount', 'date')
            ->toArray();
        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $incomeData,
                    'backgroundColor' => '#8BC34A',
                    'borderColor' => '#8BC34A'
                ],
                
                [
                    'label' => 'Expense',
                    'data' => $expenseData,
                    'backgroundColor' => '#FF5722',
                    'borderColor' => '#FF5722'
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
