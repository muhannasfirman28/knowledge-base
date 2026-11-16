<?php

namespace App\Filament\Resources\Rooms\Tables;

use App\Models\Room;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
            return $table
        ->columns([
            // 1. Nama Grup (Lokasi)
            TextColumn::make('group.name')
                ->label('Lokasi / Area')
                ->sortable()
                ->searchable()
                ->weight('bold')
                ->color('primary'),

            // 2. Nomor Kamar
            TextColumn::make('number')
                ->label('No. Kamar')
                ->sortable()
                ->searchable(),

            // 3. Status Huni (Otomatis)
            TextColumn::make('status_huni')
                ->label('Status')
                ->badge()
                ->getStateUsing(function ($record) {
                    return $record->activeTenancy()->exists() ? 'Terhuni' : 'Kosong';
                })
                ->color(fn (string $state): string => match ($state) {
                    'Terhuni' => 'danger',
                    'Kosong' => 'success',
                }),
            
            // 4. Status Publish
            ToggleColumn::make('is_published')
                ->label('Tampil di Web'),
        ])
        ->defaultSort('group.name') // Urutkan berdasarkan grup dulu
        ->groups([
            // Fitur Grouping bawaan Filament (Opsional, biar makin rapi)
            Group::make('group.name')
                ->label('Lokasi'),
        ])
        ->filters([
            SelectFilter::make('group_id')
                ->relationship('group', 'name')
                ->label('Filter Lokasi'),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }
}
