<?php

namespace App\Filament\Resources\Bills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenancy.room.number')
                    ->label('Kamar')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tenancy.mainTenant.name')
                    ->label('Penyewa')
                    ->searchable(),

                TextColumn::make('month_name')
                    ->label('Periode')
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status_bayar')
                    ->label('Status Bayar')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->is_paid ? 'Lunas' : 'Belum Lunas';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Belum Lunas' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Lunas' => 'heroicon-o-check-circle',
                        'Belum Lunas' => 'heroicon-o-x-circle',
                        default => null,
                    }),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'paid' => 'Lunas',
                        'unpaid' => 'Belum Bayar',
                    ]),
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
