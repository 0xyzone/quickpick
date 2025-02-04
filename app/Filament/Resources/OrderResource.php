<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Order;
use App\Models\Company;
use App\Models\Payment;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Rawilk\Printing\Facades\Printing;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextInputColumn;
use Rawilk\Printing\Receipts\ReceiptPrinter;
use App\Filament\Resources\OrderResource\Pages;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Filament\Resources\OrderResource\RelationManagers;

// use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $activeNavigationIcon = 'heroicon-m-clipboard-document-list';
    protected static bool $shouldSkipAuthorization = true;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id()),
                Select::make('order_type')
                    ->selectablePlaceholder(false)
                    ->options([
                        'on_site' => 'Pasal',
                        'off_site' => 'Online/Call'
                    ])
                    ->default('on_site')
                    ->reactive(),
                TextInput::make('address')
                    ->required(fn(Get $get) => $get('order_type') == 'off_site')
                    ->hidden(fn(Get $get): bool => $get('order_type') == 'off_site' ? false : true),
                Repeater::make('orderItems')
                    ->relationship()
                    ->schema([
                        Select::make('item_id')
                            ->label('Item')
                            ->searchable()
                            ->options(Item::all()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->selectablePlaceholder(false)
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
                            ->prefix('रु ')
                            ->numeric()
                            ->columnSpan(2)
                            ->formatStateUsing(fn($state): ?string => number_format($state)),
                        Hidden::make('price')
                    ])
                    ->columns(10)
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    }),
                Fieldset::make('discount')
                    ->label('Discount')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'percent' => 'Percent (%)',
                                'amount' => 'Amount'
                            ])
                            ->reactive(),
                        Select::make('percent')
                            ->selectablePlaceholder(false)
                            ->options([
                                0 => '0',
                                5 => '5',
                                10 => '10',
                                15 => '15',
                            ])
                            ->default(0)
                            ->hidden(fn(Get $get): bool => $get('type') == 'percent' ? false : true),
                        TextInput::make('discount_amount')
                            ->numeric()
                            ->hidden(fn(Get $get): bool => $get('type') == 'amount' ? false : true)
                            ->prefix('रु ')
                            ->formatStateUsing(fn($state): ?string => number_format($state)),
                    ])
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    })
                    ->columnSpan(1),
                Fieldset::make('delivery')
                    ->label('Delivery Charge')
                    ->schema([
                        Select::make('delivery_charge')
                            ->selectablePlaceholder(false)
                            ->label('Amount')
                            ->options([
                                0 => '0',
                                100 => '100',
                                150 => '150',
                            ])
                            ->formatStateUsing(fn($state): ?string => number_format($state)),
                    ])
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotals($get, $set);
                    })
                    ->columnSpan(1),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Repeater::make('payments')
                    ->relationship()
                    ->schema([
                        Select::make('payment_method')
                            ->label('Method')
                            ->live()
                            ->options([
                                'fonepay' => 'Fonepay/QR',
                                'bank' => 'Bank Transfer',
                                'esewa' => 'eSewa',
                                'cash' => 'Cash'
                            ])
                            ->searchable()
                            ->required(),
                        TextInput::make('payment_amount')
                            ->label('Amount')
                            ->required()
                            ->live()
                            ->numeric()
                            ->default(0)
                            ->minValue(1)
                            ->hidden(fn(Get $get): bool => $get('payment_method') ? false : true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            })
                            ->formatStateUsing(function (int $state): int {
                                return (int) number_format($state, 0, '', '');
                            }),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->defaultItems(0),
                Fieldset::make('Total')
                    ->schema([
                        Split::make([
                            Placeholder::make('sub_total')
                                ->label('')
                                ->content(function (): string {
                                    return 'Sub Total';
                                }),
                            Placeholder::make('sub_total')
                                ->label('')
                                ->content(function (Get $get): string {
                                    return 'रु ' . number_format($get('sub_total'));
                                }),
                        ]),
                        Split::make([
                            Placeholder::make('discount_amount')
                                ->label('')
                                ->content(function (): string {
                                    return 'Discount Amount';
                                }),
                            Placeholder::make('discount_amount')
                                ->label('')
                                ->content(function (Get $get): string {
                                    return 'रु ' . number_format($get('discount_amount'));
                                }),
                        ])
                            ->hidden(fn(Get $get): bool => $get('discount_amount') > 0 ? false : true),
                        Split::make([
                            Placeholder::make('delivery')
                                ->label('')
                                ->content(function (): string {
                                    return 'Delivery Charge';
                                }),
                            Placeholder::make('charge_amount')
                                ->label('')
                                ->content(function (Get $get): string {
                                    return 'रु ' . number_format($get('delivery_charge'));
                                }),
                        ])
                            ->hidden(fn(Get $get): bool => $get('delivery_charge') > 0 ? false : true),
                        Split::make([
                            Placeholder::make('total')
                                ->label('')
                                ->content(function (): string {
                                    return 'Grand Total';
                                }),
                            Placeholder::make('total')
                                ->label('')
                                ->content(function (Get $get): string {
                                    return 'रु ' . number_format($get('total'));
                                }),
                        ]),
                        Split::make([
                            Placeholder::make('received_payments')
                                ->label('')
                                ->content(function (): string {
                                    return 'Received Payment';
                                }),
                            Placeholder::make('received_payments')
                                ->label('')
                                ->content(function (Get $get, $record): string {
                                    if ($record && $record->payments) {
                                        foreach ($record->payments as $payment) {
                                            $paymentAmount[] = $payment->payment_amount;
                                        }
                                        $amount = array_sum($paymentAmount);
                                        return 'रु ' . ($get('received_payments') ? number_format($get('received_payments')) : number_format($amount));
                                    } else {
                                        return 'रु ' . number_format($get('received_payments'));
                                    }
                                }),
                        ])
                            ->hidden(fn(Get $get, $record): bool => ($get('payments') || ($record && $record->payments->count() > 0)) ? false : true)
                        ,
                        Split::make([
                            Placeholder::make('dueLabel')
                                ->label('')
                                ->content(function (Get $get, Order $record): string {
                                    if ($record->payments->count() > 0) {
                                        foreach ($record->payments as $payment) {
                                            $paymentAmount[] = $payment->payment_amount;
                                        }
                                        $amount = $record->total - array_sum($paymentAmount);
                                        if ($get('due')) {
                                            if ($get('due') <= 0) {
                                                return 'Return Amount';
                                            }

                                            return 'Due';

                                        }
                                        if ($amount <= 0) {
                                            return 'Return Amount';
                                        } else {
                                            return 'Due';
                                        }

                                    } else {
                                        if ($get('due')) {
                                            if ($get('due') <= 0) {
                                                return 'Return Amount';
                                            }

                                            return 'Due';

                                        } else {
                                            return '';
                                        };
                                    }
                                }),
                            Placeholder::make('due')
                                ->label('')
                                ->content(function (Get $get, Order $record): string {
                                    if ($record->payments->count() > 0) {
                                        foreach ($record->payments as $payment) {
                                            $paymentAmount[] = $payment->payment_amount;
                                        }
                                        $amount = $record->total - array_sum($paymentAmount);
                                        if ($get('due')) {
                                            if ($get('due') <= 0) {
                                                return 'रु ' . number_format($get('due') - (2 * $get('due')));
                                            }

                                            return 'रु ' . number_format($get('due'));

                                        }
                                        if ($amount <= 0) {
                                            return 'रु ' . $amount - (2 * $amount);
                                        } else {
                                            return 'रु ' . $amount;
                                        }

                                    } else {
                                        if ($get('due')) {
                                            if ($get('due') <= 0) {
                                                return 'रु ' . number_format($get('due') - (2 * $get('due')));
                                            }

                                            return 'रु ' . number_format($get('due'));

                                        } else {
                                            return '';
                                        };
                                    }
                                }),
                        ])->hidden(fn(Get $get, $record): bool => ($get('due') || ($record && $record->payments->count() > 0)) ? false : true),
                        Hidden::make('sub_total'),
                        Hidden::make('discount_amount'),
                        Hidden::make('total'),
                    ])->columns(1),
            ])
            ->columns(2);
    }

    public static function updateTotals(Get $get, Set $set, $initial = false): void
    {
        $selectedProducts = collect($get('orderItems'))->filter(fn($item) => !empty ($item['item_id']) && !empty ($item['quantity']));
        $prices = Item::find($selectedProducts->pluck('item_id'))->pluck('price', 'id');
        $subtotal = $selectedProducts->reduce(function ($subtotal, $product) use ($prices) {
            return $subtotal + ($prices[$product['item_id']] * $product['quantity']);
        }, 0);
        $discountType = $get('type');
        $discount = 0;
        $deliveryCharge = 0;
        if ($discountType == 'percent') {
            $percent = $get('percent');
            $discount = $subtotal * ($percent / 100);
        }
        if ($discountType == 'amount') {
            $discount = $get('discount_amount');
        }
        $deliveryCharge = $get('delivery_charge');

        // Payments
        $receivedPayments = 0;
        $due = 0;
        $payments = collect($get('payments'));
        // dd($payments);
        $receivedPayments = $payments->sum('payment_amount');
        $due = $get('total') - $receivedPayments;

        // Setting Values
        $set('sub_total', $subtotal);
        $set('delivery_charge', $deliveryCharge);
        $set('discount_amount', $discount);
        $set('due', $due);
        $set('total', $subtotal - $discount + $deliveryCharge);
        $set('received_payments', $receivedPayments);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->recordClasses(fn(Model $record) => match ($record->status) {
                'pending' => null,
                'preparing' => '!bg-yellow-600/20 hover:!bg-yellow-800/20',
                'ready' => '!bg-indigo-700/20 hover:!bg-indigo-800/20',
                'out_delivery' => '!bg-cyan-500/20 hover:!bg-cyan-600/20',
                'delivered' => '!bg-emerald-500/20 hover:!bg-emerald-600/20',
                'cancelled' => '!bg-red-500/20 hover:!bg-red-600/20',
                default => null
            })
            ->striped()
            ->deferLoading()
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Ordered By'),
                TextColumn::make('address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('-')
                    ->limit(25),
                TextColumn::make('orderItems')
                    ->formatStateUsing(fn($state, Get $get): string => $state->item->name . ' (x' . ($state->quantity == floor($state->quantity) ? number_format($state->quantity) : number_format($state->quantity, 1)) . ')')
                    ->listWithLineBreaks()
                    ->limitList(4)
                    ->badge(),
                IconColumn::make('order_type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Type')
                    ->icon(fn(string $state): string => match ($state) {
                        'on_site' => 'heroicon-o-building-storefront',
                        'off_site' => 'heroicon-o-wifi'
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'on_site' => 'white',
                        'off_site' => 'white'
                    }),
                TextColumn::make('total')
                    ->prefix('Rs. '),
                SelectColumn::make('status')
                    ->rules(['required'])
                    ->selectablePlaceholder(false)
                    ->options([
                        'pending' => 'Pending',
                        'preparing' => 'Being Prepared',
                        'ready' => 'Ready',
                        'out_delivery' => 'Out for delivery',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                TextInputColumn::make('notes')
                    ->label('Remarks'),
                TextColumn::make('created_at')
                    ->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Print Invoice')
                    ->url(function (Model $record) {
                        return URL::route('invoice.print', ['order' => $record]);
                    }, shouldOpenInNewTab: true)
                    ->button()
                    ->visible(fn(Model $record): bool => $record->status != 'cancelled')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function printInvoice(Order $order)
    {
        // Generate and print the invoice for the given order
        // You can customize this logic as per your requirement
        // For example, you can redirect to a route that handles invoice printing

        // For demonstration purpose, let's assume we redirect to a route
        return redirect()->route('invoice.print', ['order' => $order]);
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
            'create' => Pages\CreateOrder::route('/create'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
