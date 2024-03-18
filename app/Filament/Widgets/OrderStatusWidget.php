<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatusWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
            ->url(OrderResource::getUrl() . '?activeTab=pending')
            ->extraAttributes([
                'class' => 'hover:scale-105 duration-300'
            ]),
            Stat::make('Being Prepared Orders', Order::where('status', 'preparing')->count())
            ->url(OrderResource::getUrl() . '?activeTab=preparing')
            ->extraAttributes([
                'class' => 'hover:scale-105 duration-300 hover:bg-primary-300'
            ]),
            Stat::make('Prepared Orders', Order::where('status', 'ready')->count())
            ->url(OrderResource::getUrl() . '?activeTab=ready')
            ->extraAttributes([
                'class' => 'hover:scale-105 duration-300'
            ]),
            Stat::make('On Delivery Orders', Order::where('status', 'out_delivery')->count())
            ->url(OrderResource::getUrl() . '?activeTab=out_delivery')
            ->extraAttributes([
                'class' => 'hover:scale-105 duration-300'
            ]),
            Stat::make('Delivered Orders', Order::where('status', 'delivered')->count())
            ->url(OrderResource::getUrl() . '?activeTab=delivered')
            ->extraAttributes([
                'class' => 'hover:scale-105 duration-300'
            ]),
            Stat::make('Cancelled Orders', Order::where('status', 'cancelled')->count())
            ->url(OrderResource::getUrl() . '?activeTab=cancelled')
            ->extraAttributes([
                'class' => 'hover:scale-105 duration-300'
            ]),
        ];
    }
}
