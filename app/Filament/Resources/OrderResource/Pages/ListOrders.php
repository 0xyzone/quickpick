<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'pending' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending')),
            'being_prepared' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'preparing')),
            'ready' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'ready')),
            'out_for_delivery' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'out_delivery')),
            'delivered' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'cancelled')),
        ];
    }
}
