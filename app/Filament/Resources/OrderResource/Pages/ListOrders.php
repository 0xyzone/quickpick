<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
            ->badge(Order::all()->count()),
            'pending' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending'))
            ->badge(Order::query()->where('status', 'pending')->count())
            ->badgeColor('gray'),
            'being_prepared' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'preparing'))
            ->badge(Order::query()->where('status', 'preparing')->count())
            ->badgeColor('warning'),
            'ready' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'ready'))
            ->badge(Order::query()->where('status', 'ready')->count())
            ->badgeColor('purple'),
            'out_for_delivery' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'out_delivery'))
            ->badge(Order::query()->where('status', 'out_delivery')->count())
            ->badgeColor('cyan'),
            'delivered' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'delivered'))
            ->badge(Order::query()->where('status', 'delivered')->count())
            ->badgeColor('success'),
            'cancelled' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'cancelled'))
            ->badge(Order::query()->where('status', 'cancelled')->count())
            ->badgeColor('danger'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
