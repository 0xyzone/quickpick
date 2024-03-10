<?php

namespace App\Filament\Resources\HeroResource\Pages;

use App\Filament\Resources\HeroResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageHeroes extends ManageRecords
{
    protected static string $resource = HeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
