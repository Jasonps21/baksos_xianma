<?php

namespace App\Filament\Resources\AmbilBeras\Pages;

use App\Filament\Resources\AmbilBerasResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAmbilBeras extends CreateRecord
{
    protected static string $resource = AmbilBerasResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data berhasil disimpan';
    }

    // Setelah simpan, langsung buka form create kosong lagi
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('create');
    }
}
