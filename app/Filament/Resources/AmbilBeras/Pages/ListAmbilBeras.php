<?php

namespace App\Filament\Resources\AmbilBeras\Pages;

use App\Filament\Resources\AmbilBerasResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions; // <-- v4 pakai Actions namespace ini

class ListAmbilBeras extends ListRecords
{
    protected static string $resource = AmbilBerasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // <-- ganti ke ini
        ];
    }
}
