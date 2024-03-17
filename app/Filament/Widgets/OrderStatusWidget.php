<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatusWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Orders', Order::where('status', 'pending')->count()),
            Stat::make('Being Prepared Orders', Order::where('status', 'preparing')->count()),
            Stat::make('Prepared Orders', Order::where('status', 'ready')->count()),
            Stat::make('On Delivery Orders', Order::where('status', 'out_delivery')->count()),
            Stat::make('Delivered Orders', Order::where('status', 'delivered')->count()),
            Stat::make('Cancelled Orders', Order::where('status', 'cancelled')->count()),
        ];
    }
}
