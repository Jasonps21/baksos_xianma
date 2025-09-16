<?php

namespace App\Filament\Resources\AmbilBeras\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AmbilBerasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('no_kupon')
                    ->default(null),
                TextInput::make('no_ktp')
                    ->default(null),
                TextInput::make('nama')
                    ->default(null),
                TextInput::make('alamat')
                    ->default(null),
                DateTimePicker::make('tgl_pengambil'),
                Toggle::make('status_ambil'),
                TextInput::make('create_by')
                    ->default(null),
                DateTimePicker::make('create_date'),
                TextInput::make('update_by')
                    ->default(null),
                DateTimePicker::make('update_date'),
            ]);
    }
}
