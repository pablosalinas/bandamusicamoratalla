<?php

namespace App\Filament\Resources\InstrumentCatalogResource\Pages;

use App\Filament\Resources\InstrumentCatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstrumentCatalogs extends ListRecords
{
    protected static string $resource = InstrumentCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
