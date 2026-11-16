<?php

namespace App\Filament\Resources\Groups\RelationManagers;

use App\Models\Room;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->label('Nomor Kamar')
                    ->required()
                    ->maxLength(255),

                // Fitur Publish
                Toggle::make('is_published')
                    ->label('Publish ke Web')
                    ->default(true),

                // Fitur Pilih Fasilitas
                CheckboxList::make('facilities')
                    ->label('Fasilitas')
                    ->relationship('facilities', 'name')
                    ->columns(2)
                    ->gridDirection('row')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                TextColumn::make('number')
                    ->label('No. Kamar')
                    ->sortable()
                    ->searchable(),

                // Status Huni Otomatis
                TextColumn::make('status_huni')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Room $record) {
                        return $record->activeTenancy()->exists() ? 'Terhuni' : 'Kosong';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Terhuni' => 'danger',
                        'Kosong' => 'success',
                    }),

                ToggleColumn::make('is_published')
                    ->label('Tampil'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
