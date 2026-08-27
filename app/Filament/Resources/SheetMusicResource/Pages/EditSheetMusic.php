<?php

namespace App\Filament\Resources\SheetMusicResource\Pages;

use App\Filament\Resources\SheetMusicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSheetMusic extends EditRecord
{
    protected static string $resource = SheetMusicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
