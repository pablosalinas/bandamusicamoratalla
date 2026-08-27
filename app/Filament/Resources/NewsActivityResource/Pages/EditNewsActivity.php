<?php

namespace App\Filament\Resources\NewsActivityResource\Pages;

use App\Filament\Resources\NewsActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsActivity extends EditRecord
{
    protected static string $resource = NewsActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
