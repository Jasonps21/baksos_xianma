<?php

namespace App\Filament\Resources\AmbilBeras\Pages;

use App\Filament\Resources\AmbilBerasResource;;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAmbilBeras extends EditRecord
{
    protected static string $resource = AmbilBerasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
