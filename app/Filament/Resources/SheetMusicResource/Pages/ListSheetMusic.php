<?php

namespace App\Filament\Resources\SheetMusicResource\Pages;

use App\Filament\Resources\SheetMusicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSheetMusic extends ListRecords
{
    protected static string $resource = SheetMusicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
