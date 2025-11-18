<?php

namespace App\Filament\Resources\Tenancies\Schemas;

use App\Models\Room;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenancyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Sewa')
                ->schema([
                Select::make('room_id')
                    ->label('Pilih Kamar')
                    ->required()
                    ->searchable()
                    ->options(function ($operation, $record) {
                            return Room::query()
                                ->where('is_published', true)
                                ->where(function($query) use ($operation, $record) {
                                    $query->whereDoesntHave('tenancies', function ($q) {
                                        $q->where('is_active', true);
                                    });
                                    if ($operation === 'edit' && $record) {
                                        $query->orWhere('id', $record->room_id);
                                    }
                                })
                                ->pluck('number', 'id'); 
                        })
                        ->exists('rooms', 'id'),
                                    
                    DatePicker::make('start_date')
                        ->label('Mulai Huni')
                        ->default(now())
                        ->required(),

                    TextInput::make('price_per_month')
                        ->label('Harga Per Bulan')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Status Huni')
                        ->helperText('Jika dimatikan, sewa tidak akan muncul di pilihan sewa.')
                        ->default(true)
                        ->required(),
                ])->columns(3),

            Section::make('Data Penghuni')
                ->description('Penghuni dapat diisi lebih dari satu orang.')
                ->schema([
                    Repeater::make('tenants')
                        ->relationship()
                        ->label('Daftar Orang')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required(),
                            TextInput::make('phone_number')
                                ->label('No. HP (WA)')
                                ->tel()
                                ->required(),
                            TextInput::make('email')
                                ->label('Email')
                                ->email(),
                            FileUpload::make('ktp_image')
                                ->label('Foto KTP')
                                ->disk('public')
                                ->directory('ktp-uploads')
                                ->image()
                                ->required(),
                            Toggle::make('is_main_tenant')
                                ->label('Penanggung Jawab')
                                ->helperText('Pilih jika orang ini adalah penanggung jawab.')
                                ->default(false),
                        ])
                        ->columns(2)
                        ->minItems(1)
                        ->defaultItems(1)
                ])
            ]);
    }
}
