<?php

namespace App\Filament\Resources\AmbilBeras\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AmbilBerasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_kupon')
                    ->searchable(),
                TextColumn::make('no_ktp')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('alamat')
                    ->searchable(),
                TextColumn::make('tgl_pengambil')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('status_ambil')
                    ->boolean(),
                TextColumn::make('create_by')
                    ->searchable(),
                TextColumn::make('create_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('update_by')
                    ->searchable(),
                TextColumn::make('update_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
