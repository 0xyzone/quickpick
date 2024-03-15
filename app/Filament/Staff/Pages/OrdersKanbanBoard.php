<?php

namespace App\Filament\Staff\Pages;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class OrdersKanbanBoard extends KanbanBoard
{
    protected static string $model = Order::class;
    protected static string $recordTitleAttribute = 'id';
    protected static ?int $navigationSort = 2;
    // protected static string $statusEnum = OrderStatus::class;

    protected function statuses(): Collection
    {
        return collect([
            ['id' => 'pending', 'title' => 'New'],
            ['id' => 'preparing', 'title' => 'Being Prepared'],
            ['id' => 'ready', 'title' => 'Ready'],
            // ['id' => 'out_delivery', 'title' => 'Out for delivery'],
        ]);
    }

    protected function records(): Collection
    {
        return Order::ordered()->get();
    }

    public function onSortChanged(int $recordId, string $status, array $orderedIds): void
    {
        Order::setNewOrder($orderedIds);
    }

    protected function additionalRecordData(Model $record): Collection
    {
        return collect([
            'items' => $record,
            'outdelivery' => Order::where('status','out_delivery')->get()
        ]);
    }

    public bool $disableEditModal = true;
}
