<?php

namespace App\Filament\Resources\Tenancies;

use App\Filament\Resources\Tenancies\Pages\CreateTenancy;
use App\Filament\Resources\Tenancies\Pages\EditTenancy;
use App\Filament\Resources\Tenancies\Pages\ListTenancies;
use App\Filament\Resources\Tenancies\Schemas\TenancyForm;
use App\Filament\Resources\Tenancies\Tables\TenanciesTable;
use App\Models\Tenancy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TenancyResource extends Resource
{
    protected static ?string $model = Tenancy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = "Penyewaan";

    protected static string|UnitEnum|null $navigationGroup = 'Sewa';

    public static function form(Schema $schema): Schema
    {
        return TenancyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenanciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenancies::route('/'),
            'create' => CreateTenancy::route('/create'),
            'edit' => EditTenancy::route('/{record}/edit'),
        ];
    }
}
