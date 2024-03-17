<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalOrdersToday extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        $topSellingQuery = OrderItem::whereDate('created_at', Carbon::today());
        // Fetch the best selling item
        $bestSellingItem = OrderItem::select('item_id', DB::raw('SUM(quantity) as total_quantity_sold'))
            ->groupBy('item_id')
            ->orderByDesc('total_quantity_sold')
            ->first();

        if ($bestSellingItem) {
            $item = $bestSellingItem->item; // Utilize the relationship to fetch item details

            // Create the Stat with the best selling item's name
            if ($item) {
                $itemName = $item->name;
            } else {
                $itemName =  'Item details not found';
            }
        } else {
            $itemName =  'No items sold yet';
        }
        return [
            Stat::make('Top Selling Product', $itemName)
            ->extraAttributes([
                'class' => 'bg-gradient-to-t from-lime-500 to-lime-950 hover:scale-105 duration-300'
            ]),
            Stat::make('Total Orders This Month', Order::whereMonth('created_at', Carbon::now()->month)->count())
            ->extraAttributes([
                'class' => 'dark:bg-gradient-to-t from-amber-500 to-amber-950 hover:scale-105 duration-300'
            ]),
            Stat::make('Total Orders Today', Order::whereDate('created_at', Carbon::today())->count())
            ->extraAttributes([
                'class' => 'dark:bg-gradient-to-t from-violet-500 to-violet-950 hover:scale-105 duration-300'
            ]),
            Stat::make('Total Orders This Year', Order::whereYear('created_at', Carbon::now()->year)->count())
            ->extraAttributes([
                'class' => 'dark:bg-gradient-to-t from-sky-500 to-sky-950 hover:scale-105 duration-300'
            ]),
            Stat::make('Total earnings this month', 'रु ' . Order::whereMonth('created_at', Carbon::now()->month)->whereIn('status', ['ready', 'delivered'])->sum('total'))
            ->extraAttributes([
                'class' => 'bg-gradient-to-t from-amber-500 to-amber-950 hover:scale-105 duration-300'
            ]),
            Stat::make('Total earnings Today', 'रु ' . Order::whereDate('created_at', Carbon::today())->whereIn('status', ['ready', 'delivered'])->sum('total'))
            ->extraAttributes([
                'class' => 'bg-gradient-to-t from-violet-500 to-violet-950 hover:scale-105 duration-300'
            ]),
        ];
    }
}
