<?php

namespace App\Filament\Resources\NewsActivityResource\Pages;

use App\Filament\Resources\NewsActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsActivities extends ListRecords
{
    protected static string $resource = NewsActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
