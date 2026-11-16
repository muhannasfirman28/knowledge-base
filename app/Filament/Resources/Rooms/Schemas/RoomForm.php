<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('group_id')
                    ->relationship('group', 'name')
                    ->label('Lokasi/Area')
                    ->required(),
                TextInput::make('number')
                    ->label('Nomor Kamar')
                    ->required(),
                CheckboxList::make('facilities')
                    ->label('Fasilitas Kamar')
                    ->relationship('facilities', 'name') // Relasi ke model Facility, tampilkan kolom 'name'
                    ->columns(3) // Tampilkan 3 kolom biar rapi
                    ->gridDirection('row')
                    ->bulkToggleable(),
                Toggle::make('is_published')
                    ->label('Publish Kamar')
                    ->helperText('Jika dimatikan, kamar tidak akan muncul di pilihan sewa.')
                    ->default(true)
                    ->required(),
            ]);
    }
}
