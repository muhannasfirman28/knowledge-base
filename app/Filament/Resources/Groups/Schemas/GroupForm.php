<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('Contoh: Kost Mawar / Lantai 1'),
                Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->rows(3)
                    ->required(),
                Textarea::make('google_maps_embed')
                    ->label('Google Maps Embed Code')
                    ->placeholder('Paste kode <iframe...> dari Google Maps disini')
                    ->rows(5)
                    ->columnSpanFull()
                    ->helperText('Buka Google Maps -> Klik Share -> Embed a map -> Copy HTML'),
                Section::make('Galeri Foto')
                ->schema([
                    FileUpload::make('gallery')
                        ->label('Foto-foto Lokasi')
                        ->disk('public')
                        ->multiple() // <--- INI KUNCINYA: BISA UPLOAD BANYAK
                        ->reorderable() // Bisa geser urutan foto
                        ->image()
                        ->directory('groups-gallery') // Folder penyimpanan di storage
                        ->columnSpanFull()
                        ->helperText('Bisa upload lebih dari satu foto. Geser untuk mengatur urutan.'),
                ]),
            ]);
    }
}
