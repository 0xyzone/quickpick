<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Filament\Support\RawJs;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalOrdersPerMonthChart extends ApexChartWidget
{
    protected static ?int $sort = 4;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'totalOrdersChartMonth';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Orders Per Month';

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->startOfYear()),
            DatePicker::make('date_end')
                ->default(now()->endOfYear()),
        ];
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {

        $data = Trend::model(Order::class)
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();
        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'TotalOrdersChart',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' => $data->map(fn(TrendValue $value) => $value->date),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }
}
