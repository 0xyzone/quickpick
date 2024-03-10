<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $activeNavigationIcon = 'heroicon-m-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                FileUpload::make('category_photo_path')
                    ->image()
                    ->directory('categories.img')
                    ->moveFiles()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()
                ->columns(1)
                ->schema([
                    ImageColumn::make('category_photo_path')
                    ->label('Img')
                    ->grow(false),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->columnSpanFull(),
                ]),
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->paginationPageOptions([9,18,27])
            ->defaultSort('id', 'desc')
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
            'index' => Pages\ListCategories::route('/'),
            // 'create' => Pages\CreateCategory::route('/create'),
            // 'view' => Pages\ViewCategory::route('/{record}'),
            // 'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
