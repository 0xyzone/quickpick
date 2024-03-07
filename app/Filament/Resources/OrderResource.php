<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $activeNavigationIcon = 'heroicon-m-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                ->default(auth()->id()),
                Select::make('order_type')
                    ->options([
                        'on_site' => 'Pasal',
                        'off_site' => 'Online/Call'
                    ])
                    ->default('on_site'),
                TextInput::make('address'),
                Repeater::make('orderItems')
                    ->relationship()
                    ->schema([
                        Select::make('item_id')
                            ->label('Item')
                            ->searchable()
                            ->options(Item::all()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $product = Item::find($state);
                                $quantity = $get('quantity');
                                if ($product) {
                                    $set('price', $product->price * $quantity);
                                }
                            })
                            ->columnSpan(6),
                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(0.5)
                            ->step(0.5)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $id = $get('item_id');
                                $product = Item::find($id);
                                $quantity = $state;
                                if ($product) {
                                    $set('price', $product->price * $quantity);
                                }
                            })
                            ->inputMode('decimal')
                            ->default(1)
                            ->columnSpan(2),
                        TextInput::make('price')
                            ->dehydrated(false)
                            ->disabled()
                            ->prefix('Rs.')
                            ->numeric()
                            ->columnSpan(2),
                        Hidden::make('price')
                    ])
                    ->columns(10)
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    }),
                Textarea::make('notes'),
                Fieldset::make('discount')
                    ->label('Discount')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'percent' => 'Percent (%)',
                                'amount' => 'Amount'
                            ])
                            ->default('percent')
                            ->dehydrated(false)
                            ->reactive(),
                        Select::make('percent')
                            ->options([
                                0 => '0',
                                5 => '5',
                                10 => '10',
                                15 => '15',
                            ])
                            ->default(0)
                            ->dehydrated(false)
                            ->hidden(fn(Get $get): bool => $get('type') == 'percent' ? false : true),
                        TextInput::make('amount')
                            ->numeric()
                            ->dehydrated(false)
                            ->hidden(fn(Get $get): bool => $get('type') == 'amount' ? false : true)
                    ])
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    })
                    ->columnSpan(1),
                Fieldset::make('Total')
                    ->schema([
                        TextInput::make('sub_total')
                            ->label('Sub Total')
                            ->numeric()
                            ->readOnly()
                            ->dehydrated(false)
                            ->prefix('Rs.'),
                        TextInput::make('discount_amount')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rs.'),
                        TextInput::make('total')
                            ->label('Grand Total')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rs.'),
                    ])->columns(3),
            ])
            ->columns(2);
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $selectedProducts = collect($get('orderItems'))->filter(fn($item) => !empty ($item['item_id']) && !empty ($item['quantity']));
        $prices = Item::find($selectedProducts->pluck('item_id'))->pluck('price', 'id');
        $subtotal = $selectedProducts->reduce(function ($subtotal, $product) use ($prices) {
            return $subtotal + ($prices[$product['item_id']] * $product['quantity']);
        }, 0);
        $discountType = $get('type');
        if ($discountType == 'percent') {
            $percent = $get('percent');
            $discount = $subtotal * ($percent / 100);
        }
        if ($discountType == 'amount') {
            $amount = $get('amount');
            $discount = $amount;
        }
        $set('sub_total', $subtotal);
        $set('discount_amount', $discount);
        $set('total', $subtotal - $discount);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('id', 'desc')
            ->deferLoading()
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Ordered By'),
                TextColumn::make('address')
                    ->default('-')
                    ->limit(25),
                IconColumn::make('order_type')
                    ->label('Type')
                    ->icon(fn(string $state): string => match ($state) {
                        'on_site' => 'heroicon-o-building-storefront',
                        'off_site' => 'heroicon-o-wifi'
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'on_site' => 'primary',
                        'off_site' => 'warning'
                    }),
                TextColumn::make('total')
                ->prefix('Rs. '),
                SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'preparing' => 'Being Prepared',
                        'ready' => 'Ready',
                        'out_delivery' => 'Out for delivery'
                    ]),
                TextInputColumn::make('notes')
                    ->label('Remarks')
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->poll('10s');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
