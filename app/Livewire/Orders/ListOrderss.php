<?php

namespace App\Livewire\Orders;

use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Columns\Column;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Concerns\HasTabs;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListOrderss extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    use HasTabs;

    public function getRenderHookScopes(): array
    {
        return [static::class];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query())
            ->defaultSort('id', 'desc')
            ->recordClasses(fn(Model $record) => match ($record->status) {
                'pending' => null,
                'preparing' => '!bg-yellow-600/20',
                'ready' => '!bg-indigo-700/20',
                'out_delivery' => '!bg-cyan-500/20',
                'delivered' => '!bg-emerald-500/20',
                'cancelled' => '!bg-red-500/20',
                default => null
            })
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Split::make([
                            TextColumn::make('id')
                                ->searchable()
                                ->icon('heroicon-o-hashtag')
                                ->iconColor('white')
                                ->prefix('Order ')
                                ->extraAttributes([
                                    'class' => '!text-2xl'
                                ]),
                            SelectColumn::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'preparing' => 'Being Prepared',
                                    'ready' => 'Ready',
                                    'out_delivery' => 'Out for delivery',
                                    'delivered' => 'Delivered',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->disabled(fn($state): bool => $state == 'pending' ? false : ($state == 'preparing' ? false : true))
                                ->alignment(Alignment::End)
                                ->columnSpanFull()
                        ]),
                        Panel::make([
                            Split::make([
                                TextColumn::make('orderItems.item.name')
                                    ->description('Items', position: 'above')
                                    ->listWithLineBreaks()
                                    ->extraAttributes([
                                        'class' => 'my-4'
                                    ]),
                                TextColumn::make('orderItems.quantity')
                                    ->description('Quantity', position: 'above')
                                    ->listWithLineBreaks()
                                    ->alignment(Alignment::End),
                            ]),
                            Panel::make([
                                TextColumn::make('notes')
                                    ->description('Notes:', position: 'above'),
                            ])
                                ->hidden(fn($record): bool => $record->notes !== null ? false : true)
                                ->extraAttributes([
                                    'class' => '!bg-gray-800/20'
                                ])
                        ])
                            ->extraAttributes([
                                'class' => 'mt-2'
                            ]),
                    ])
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->poll('1s')
            ->deferLoading();
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

    public function render(): View
    {
        return view('livewire.orders.list-orderss');
    }
}
