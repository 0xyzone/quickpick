<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Hero;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HeroResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HeroResource\RelationManagers;

class HeroResource extends Resource
{
    protected static ?string $model = Hero::class;
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $activeNavigationIcon = 'heroicon-m-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('header')
                    ->required(),
                TextInput::make('cta')
                    ->prefix(env('APP_URL')),
                RichEditor::make('description')
                    ->disableToolbarButtons([
                        'blockquote',
                        'strike',
                        'attachFiles',
                        'bulletList',
                        'codeBlock',
                        'link',
                        'orderedList',
                        'h1',
                        'h2',
                        'h3',
                        'strike',
                    ])
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('background_photo_path')
                    ->label('Banner')
                    ->required()
                    ->image()
                    ->columnSpanFull(),
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
                        TextColumn::make('header'),
                        TextColumn::make('description')
                            ->html(),
                        // ViewColumn::make('description')->view('tables.columns.hero-description'),
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
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHeroes::route('/'),
        ];
    }
}
