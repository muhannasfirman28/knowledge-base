<?php

namespace App\Filament\Resources\Rooms\Schemas;

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
                Toggle::make('is_published')
                    ->label('Publish Kamar')
                    ->helperText('Jika dimatikan, kamar tidak akan muncul di pilihan sewa.')
                    ->default(true)
                    ->required(),
            ]);
    }
}
