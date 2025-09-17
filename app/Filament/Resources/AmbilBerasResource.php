<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmbilBeras\Pages;
use App\Models\AmbilBeras;
use Closure;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // v4
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\RawJs;


class AmbilBerasResource extends Resource
{
    protected static ?string $model = AmbilBeras::class;

    // PAKAI TIPE YANG SAMA PERSIS DENGAN PARENT:
    // (1) opsi enum:
    protected static \BackedEnum|string|null $navigationIcon = Heroicon::ClipboardDocumentList;

    // (2) atau opsi string biasa:
    // protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Daftar Ambil Beras';
    protected static ?string $pluralLabel = 'Daftar Ambil Beras';

    // v4 pakai Schema (bukan Forms\Form)
    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Forms\Components\TextInput::make('no_kupon')
                ->label('No Kupon')
                ->tel()
                ->extraInputAttributes([
                    'inputmode' => 'numeric',
                    'pattern'   => '[0-9]*',
                ])
                ->required()
                ->maxLength(10)
                ->rule(function () {
                    return function (string $attribute, $value, Closure $fail) {
                        $q = \App\Models\AmbilBeras::query()->where('no_kupon', $value);
                        if ($id = request()->route('record')) $q->where('id', '!=', $id);
                        if ($q->exists()) $fail('No kupon telah ada.');
                    };
                }),

            // NO KTP — tepat 16 digit, string
            Forms\Components\TextInput::make('no_ktp')
                ->label('No KTP')
                ->tel()
                ->extraInputAttributes([
                    'inputmode' => 'numeric',
                    'pattern'   => '[0-9]*',
                ])
                ->required()
                ->minLength(16)->maxLength(16)
                ->rule(function () {
                    return function (string $attribute, $value, Closure $fail) {
                        $existing = \App\Models\AmbilBeras::query()
                            ->select(['id', 'no_kupon'])
                            ->where('no_ktp', $value)
                            ->when($id = request()->route('record'), fn($q) => $q->where('id', '!=', $id))
                            ->first();
                        if ($existing) $fail('Data No KTP telah ada tersimpan / terinput pada nomor kupon ' . ($existing->no_kupon ?? '-'));
                    };
                })
                ->helperText('Harus 16 digit angka.'),

            Forms\Components\TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('alamat')
                ->label('Alamat')
                ->required()
                ->maxLength(255)
                ->rows(2)
                ->rule(function () {
                    return function (string $attribute, $value, Closure $fail) {
                        $q = AmbilBeras::query()->where('alamat', $value);
                        if ($id = request()->route('record')) {
                            $q->where('id', '!=', $id);
                        }
                        if ($q->exists()) {
                            $fail('Alamat telah tersimpan / telah ada.');
                        }
                    };
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([25, 50, 100, 200])
            ->defaultPaginationPageOption(50) // default 50/baris
            ->columns([
                Tables\Columns\TextColumn::make('no_kupon')->label('No Kupon')->searchable(),
                Tables\Columns\TextColumn::make('no_ktp')->label('No KTP')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat')->wrap()->searchable(),
            ])
            // klik baris langsung ke halaman edit
            ->recordUrl(fn($record) => static::getUrl('edit', ['record' => $record]))
            ->actions([])        // tidak perlu row action
            ->defaultSort('no_kupon', 'asc')
            ->bulkActions([]);   // tidak ada bulk action
    }
    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        // Mulai dari query bawaan resource
        return parent::getEloquentQuery()
            ->select(['id', 'no_kupon', 'no_ktp', 'nama', 'alamat']); // kolom yang dipakai tabel
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAmbilBeras::route('/'),
            'create' => Pages\CreateAmbilBeras::route('/create'),
            'edit'   => Pages\EditAmbilBeras::route('/{record}/edit'),
        ];
    }
}
