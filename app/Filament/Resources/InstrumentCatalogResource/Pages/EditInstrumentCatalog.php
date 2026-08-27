<?php

namespace App\Filament\Resources\InstrumentCatalogResource\Pages;

use App\Filament\Resources\InstrumentCatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstrumentCatalog extends EditRecord
{
    protected static string $resource = InstrumentCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
