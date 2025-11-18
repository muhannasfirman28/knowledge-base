<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Models\Tenancy;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenancy_id')
                    ->label('Penyewa (Kamar)')
                    ->searchable()
                    ->options(function () {
                        return Tenancy::with(['room', 'mainTenant'])
                            ->get()
                            ->mapWithKeys(function ($tenancy) {
                                $room = $tenancy->room->number ?? 'No Room';
                                $name = $tenancy->mainTenant->name ?? 'No Name';
                                return [$tenancy->id => "{$room} - {$name}"];
                            });
                    })
                    ->required(),

                TextInput::make('month_name')
                    ->label('Periode Bulan')
                    ->placeholder('Contoh: November 2025')
                    ->required(),

                DatePicker::make('due_date')
                    ->label('Jatuh Tempo')
                    ->required(),

                TextInput::make('amount')
                    ->label('Nominal Tagihan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                    
                TextInput::make('order_sequence')
                    ->label('Urutan Bulan')
                    ->numeric()
                    ->default(1)
                    ->helperText('Urutan pembayaran (1, 2, 3...).')
                    ->required(),
            ]);
    }
}
