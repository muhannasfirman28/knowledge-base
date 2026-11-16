<?php

namespace App\Filament\Resources\Tenancies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;

class TenanciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('room.group.name')
                ->label('Lokasi')
                ->searchable(),

            TextColumn::make('room.number')
                ->label('Kamar')
                ->sortable()
                ->searchable(),

            TextColumn::make('mainTenant.name')
                ->label('Penanggung Jawab')
                ->searchable(),

            TextColumn::make('price_per_month')
                ->label('Harga/Bulan')
                ->money('IDR')
                ->sortable(),

            TextColumn::make('start_date')
                ->label('Mulai Sewa')
                ->date('d M Y')
                ->sortable(),

            ToggleColumn::make('is_active')
                ->label('Status Aktif'),

            TextColumn::make('id')
                ->label('ID Token (untuk Cek Tagihan)')
                ->copyable()
                ->copyMessage('ID Token berhasil disalin!')
                ->copyMessageDuration(1500)
                ->limit(10)
                ->searchable(),
        ])
        ->filters([
            SelectFilter::make('is_active')
                ->label('Status')
                ->options([
                    '1' => 'Aktif',
                    '0' => 'Tidak Aktif',
                ])
        ])
        ->actions([
            // ViewAction::make(),
            EditAction::make(),
            Action::make('kirim portal pembayaran')
            ->visible(fn ($record) => $record->mainTenant && $record->mainTenant->phone_number)
                ->label('Pembayaran')
                  ->url(function ($record) {
                        $nomor = preg_replace('/[^0-9]/', '', $record->mainTenant->phone_number);
                        $pesan = urlencode("Link pembayaran: " . route('payment.show', ['tenancy' => $record]));
                        return "https://wa.me/{$nomor}?text={$pesan}";
                    }, shouldOpenInNewTab: true)
                ->icon('heroicon-o-currency-dollar'),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('start_date', 'desc');
    }
}
