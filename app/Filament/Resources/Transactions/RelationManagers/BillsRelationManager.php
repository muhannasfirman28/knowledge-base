<?php

namespace App\Filament\Resources\Transactions\RelationManagers;

use App\Models\Bill;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BillsRelationManager extends RelationManager
{
    protected static string $relationship = 'bills';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenancy_id')
                    ->required(),
                TextInput::make('month_name')
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('order_sequence')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('month_name')
            ->columns([
                TextColumn::make('month_name')->label('Bulan'),
                TextColumn::make('amount')->money('IDR'),
                TextColumn::make('is_paid')
                    ->label('Status Saat Ini')
                    ->badge()
                    ->state(fn (Bill $record) => $record->is_paid ? 'Lunas' : 'Belum Lunas')
                    ->color(fn (string $state) => match ($state) {
                        'Lunas' => 'success',
                        'Belum Lunas' => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
