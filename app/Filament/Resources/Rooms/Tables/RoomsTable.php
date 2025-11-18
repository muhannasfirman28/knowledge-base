<?php

namespace App\Filament\Resources\Rooms\Tables;

use App\Models\Room;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("group.name")->sortable()->searchable()->label('Lokasi'),
                TextColumn::make('number')->sortable()->searchable()->label('No. Kamar'),
                TextColumn::make('group.name')->sortable()->label('Area'),
                TextColumn::make('status_huni')
                    ->label('Status Huni')
                    ->badge()
                    ->getStateUsing(function (Room $record) {
                        return $record->activeTenancy()->exists() ? 'Terhuni' : 'Belum Dihuni';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Terhuni' => 'danger',
                        'Belum Dihuni' => 'success',
                    }),
            
                ToggleColumn::make('is_published')
                    ->label('Publish')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->defaultSort('number')
            ->filters([
                //
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
