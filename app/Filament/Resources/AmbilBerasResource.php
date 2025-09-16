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
                ->required()
                ->maxLength(255)
                ->rule(function () {
                    return function (string $attribute, $value, Closure $fail) {
                        $q = AmbilBeras::query()->where('no_kupon', $value);
                        if ($id = request()->route('record')) {
                            $q->where('id', '!=', $id);
                        }
                        if ($q->exists()) {
                            $fail('No kupon telah ada.');
                        }
                    };
                }),

            Forms\Components\TextInput::make('no_ktp')
                ->label('No KTP')
                ->required()
                ->rule('regex:/^\d{16}$/')
                ->maxLength(255)
                ->rule(function () {
                    return function (string $attribute, $value, Closure $fail) {
                        $existing = AmbilBeras::query()
                            ->select(['id', 'no_kupon'])
                            ->where('no_ktp', $value)
                            ->when($id = request()->route('record'), fn($q) => $q->where('id', '!=', $id))
                            ->first();

                        if ($existing) {
                            $fail('Data No KTP telah ada tersimpan / terinput pada nomor kupon ' . ($existing->no_kupon ?? '-'));
                        }
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
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('no_kupon')->label('No Kupon')->searchable(),
                Tables\Columns\TextColumn::make('no_ktp')->label('No KTP')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat')->wrap(),
                Tables\Columns\TextColumn::make('tgl_pengambil')->label('Tgl Pengambil')->dateTime('Y-m-d H:i'),
                Tables\Columns\IconColumn::make('status_ambil')->boolean()->label('Status Ambil'),
                Tables\Columns\TextColumn::make('create_by')->label('Create By'),
                Tables\Columns\TextColumn::make('create_date')->label('Create Date')->dateTime('Y-m-d H:i'),
                Tables\Columns\TextColumn::make('update_by')->label('Update By'),
                Tables\Columns\TextColumn::make('update_date')->label('Update Date')->dateTime('Y-m-d H:i'),
            ])
            // klik baris langsung ke halaman edit
            ->recordUrl(fn($record) => static::getUrl('edit', ['record' => $record]))
            ->actions([])        // tidak perlu row action
            ->bulkActions([]);   // tidak ada bulk action
    }
    public static function getRelations(): array
    {
        return [];
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
