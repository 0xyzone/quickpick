<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Order;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Item::class;

    protected static ?string $navigationParentItem = 'Categories';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $activeNavigationIcon = 'heroicon-m-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('price')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('item_photo_path')
                    ->label('Item Image')
                    ->required(fn(string $operation) => $operation == 'edit')
                    ->image()
                    ->columnSpanFull()
                    ->directory('items.img')
                    ->moveFiles(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        ImageColumn::make('item_photo_path')
                            ->defaultImageUrl(url('/storage/default.jpg'))
                            ->label('Img')
                            ->size('100%')
                            ->extraImgAttributes([
                                'class' => '!object-center rounded-lg mb-2 aspect-square'
                            ]),
                        Split::make([
                            ImageColumn::make('category.category_photo_path')
                                ->grow(0)
                                ->width(15)
                                ->height(15),
                                TextColumn::make('category.name')
                        ]),
                            TextColumn::make('name')
                                ->label('Item Name')
                                ->color('primary')
                                ->searchable()
                                ->sortable(),
                        TextColumn::make('description')
                        ->wrap()
                            ->limit(120)
                            ->color('gray')
                            ->tooltip(function (TextColumn $column): ?string {
                                $state = $column->getState();

                                if (strlen($state) <= $column->getCharacterLimit()) {
                                    return null;
                                }

                                // Only render the tooltip if the column content exceeds the length limit.
                                return $state;
                            }),
                        TextColumn::make('price')
                            ->prefix('रु ')
                            ->sortable(),
                    ]),
            ])
            ->contentGrid(['md' => 2, 'xl' => 4])
            ->paginationPageOptions([2, 4, 8, 12, 'all'])
            ->defaultPaginationPageOption(4)
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
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
            'index' => Pages\ListItems::route('/'),
            // 'create' => Pages\CreateItem::route('/create'),
            // 'view' => Pages\ViewItem::route('/{record}'),
            // 'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
